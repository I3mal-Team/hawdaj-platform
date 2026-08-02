import 'dart:async';
import 'dart:collection';
import 'dart:typed_data';
import 'dart:ui' as ui;
import 'package:collection/collection.dart';

import 'package:bloc/bloc.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/routing/app_router.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/functions/open_file_link.dart';
import 'package:hawdaj/features/exploration/data/model/glodal_search/item.dart';
import 'package:hawdaj/features/exploration/presentation/manager/get_search_global_cubit/get_search_global_cubit.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_cubit.dart';
import 'package:http/http.dart' as http;

part 'map_state.dart';

class MapCubit extends Cubit<MapState> {
  MapCubit({required this.searchCubit}) : super(MapInitial());

  final GetSearchGlobalCubit searchCubit;
  MapType mapType = MapType.normal;
  bool isListSearchVisible = true;

  final Set<Marker> _markers = HashSet<Marker>();
  Set<Marker> get markers => _markers;

  String viewAs = '';
  int regionId = 0;
  int cityId = 0;

  final Completer<GoogleMapController> mapController = Completer();

  void addUserLocationMarker(LatLng position) {
    _markers.removeWhere((m) => m.markerId.value == 'current_location');

    final marker = Marker(
      markerId: const MarkerId('current_location'),
      position: position,
      infoWindow: const InfoWindow(title: 'موقعي الحالي'),
      icon: BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueAzure),
    );

    _markers.add(marker);

    emit(MarkersUpdated(Set<Marker>.unmodifiable(_markers)));
  }

  final TextEditingController searchController = TextEditingController();

  static const int _cacheCap = 128;
  final LinkedHashMap<String, BitmapDescriptor> _iconCache =
      LinkedHashMap<String, BitmapDescriptor>();

  void toggleMapTypeGroup1() {
    mapType = (mapType == MapType.normal) ? MapType.satellite : MapType.normal;
    emit(MapTypeChanged(mapType));
  }

  Future<void> moveToCurrentLocation(
    GoogleMapController controller, {
    double zoom = 6,
  }) async {
    // 1. طلب الإذن
    LocationPermission permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied ||
        permission == LocationPermission.deniedForever) {
      permission = await Geolocator.requestPermission();
      if (permission != LocationPermission.always &&
          permission != LocationPermission.whileInUse) {
        // إذا ما حصلش إذن، أوقف هنا
        return;
      }
    }

    // 2. جلب الموقع الحالي
    Position position = await Geolocator.getCurrentPosition(
      desiredAccuracy: LocationAccuracy.high,
    );

    final LatLng latLng = LatLng(position.latitude, position.longitude);

    // 3. تحريك الكاميرا بخاصية animate
    await controller.animateCamera(
      CameraUpdate.newCameraPosition(
        CameraPosition(target: latLng, zoom: zoom),
      ),
    );
  }

  void toggleMapTypeGroup2() {
    mapType = (mapType == MapType.terrain) ? MapType.hybrid : MapType.terrain;
    emit(MapTypeChanged(mapType));
  }

  void toggleListSearchVisibility() {
    isListSearchVisible = !isListSearchVisible;
    emit(ListVisibilityChanged(isListSearchVisible));
  }

  void changeViewAs(String? label) {
    viewAs = label ?? '';
    if (label == null) {
      regionId = 0;
      cityId = 0;
    }

    searchCubit.updateFilters(
      newSearch: searchController.text,
      newRegionId: regionId,
      newCityId: cityId,
      newViewAs: viewAs,
    );

    emit(ViewAsChanged(viewAs: viewAs, regionId: regionId, cityId: cityId));
  }

  void updateSearch() {
    searchCubit.updateFilters(
      newSearch: searchController.text,
      newRegionId: regionId,
      newCityId: cityId,
      newViewAs: viewAs,
    );
  }

  void clearMarkers() {
    _markers.clear();
    emit(MarkersUpdated(Set<Marker>.unmodifiable(_markers)));
  }

  Future<void> setMarkersFromItems(List<ItemGlodal> items) async {
    final currentLocationMarker = _markers.firstWhereOrNull(
      (m) => m.markerId.value == 'current_location',
    );

    clearMarkers(); // احذف كل شيء

    await _addMarkers(items); // أضف ماركرات الأماكن

    if (currentLocationMarker != null) {
      _markers.add(currentLocationMarker); // أضف ماركر الموقع الحالي من جديد
      emit(MarkersUpdated(Set<Marker>.unmodifiable(_markers)));
    }
  }

  Future<void> addMarkersFromItems(List<ItemGlodal> items) async {
    await _addMarkers(items);
  }

  Future<void> _addMarkers(List<ItemGlodal> items) async {
    final valid = items.where((e) => e.lat != null && e.long != null).toList();

    final List<Future<Marker?>> markerFutures = valid.map((item) async {
      try {
        final String? img = item.image;
        final String? idStr = item.id?.toString();
        if (img == null || img.isEmpty || idStr == null) return null;

        final BitmapDescriptor icon = await _getCircularMarkerIconFromUrl(
          "https://dashboard.hawdaj.net/$img",
          logicalSize: 56,
          borderColor: Colors.white,
          borderWidth: 2,
        );

        return Marker(
          icon: icon,
          markerId: MarkerId(idStr),
          position: LatLng(item.lat!, item.long!),
          infoWindow: InfoWindow(
            title: item.title ?? '',
            onTap: () {
              _showMarkerDetails(item);
            },
          ),
        );
      } catch (_) {
        return null;
      }
    }).toList();

    const int maxConcurrent = 5;
    for (int i = 0; i < markerFutures.length; i += maxConcurrent) {
      final batch = markerFutures.sublist(
        i,
        (i + maxConcurrent > markerFutures.length)
            ? markerFutures.length
            : i + maxConcurrent,
      );
      final results = await Future.wait(batch);
      _markers.addAll(results.whereType<Marker>());
      emit(MarkersUpdated(Set<Marker>.unmodifiable(_markers)));
    }
  }

  void _showMarkerDetails(ItemGlodal item) {
    final context = parentKey.currentContext;
    if (context == null) return;
    baseBottomSheet(
      context: context,
      child: ItemDetailsWidget(item: item),
      hideNavBar: true,
    );
  }

  Future<BitmapDescriptor> _getCircularMarkerIconFromUrl(
    String url, {
    required double logicalSize,
    Color borderColor = Colors.transparent,
    double borderWidth = 0,
    Duration timeout = const Duration(seconds: 10),
  }) async {
    final cacheKey = '$url|$logicalSize|${borderColor.value}|$borderWidth';
    final cached = _iconCache[cacheKey];
    if (cached != null) {
      _iconCache.remove(cacheKey);
      _iconCache[cacheKey] = cached;
      return cached;
    }

    final uri = Uri.parse(url);
    final http.Response response = await http.get(uri).timeout(timeout);
    if (response.statusCode != 200) {
      throw Exception('Failed to load marker image');
    }

    final Uint8List bytes = response.bodyBytes;
    final double dpr =
        ui.PlatformDispatcher.instance.views.first.devicePixelRatio;
    final int targetSize = (logicalSize * dpr).round().clamp(32, 512);

    final ui.Codec codec = await ui.instantiateImageCodec(
      bytes,
      targetWidth: targetSize,
      targetHeight: targetSize,
    );
    final ui.FrameInfo frameInfo = await codec.getNextFrame();
    final ui.Image image = frameInfo.image;

    final ui.PictureRecorder recorder = ui.PictureRecorder();
    final Canvas canvas = Canvas(recorder);
    final double size = targetSize.toDouble();
    final Rect rect = Rect.fromLTWH(0, 0, size, size);
    final Offset center = Offset(size / 2, size / 2);
    final double radius = size / 2;

    if (borderWidth > 0) {
      final Paint borderPaint = Paint()
        ..color = borderColor
        ..style = PaintingStyle.fill
        ..isAntiAlias = true;
      canvas.drawCircle(center, radius, borderPaint);
    }

    final Path clip = Path()
      ..addOval(Rect.fromCircle(center: center, radius: radius - borderWidth));
    canvas.save();
    canvas.clipPath(clip);

    final double imgW = image.width.toDouble();
    final double imgH = image.height.toDouble();
    final double srcSize = imgW < imgH ? imgW : imgH;
    final Rect src = Rect.fromCenter(
      center: Offset(imgW / 2, imgH / 2),
      width: srcSize,
      height: srcSize,
    );

    final Paint paint = Paint()..isAntiAlias = true;
    canvas.drawImageRect(image, src, rect.deflate(borderWidth), paint);
    canvas.restore();

    final ui.Image outputImage = await recorder.endRecording().toImage(
      targetSize,
      targetSize,
    );
    final ByteData? byteData = await outputImage.toByteData(
      format: ui.ImageByteFormat.png,
    );
    if (byteData == null) {
      throw Exception('Failed to encode marker image');
    }

    final Uint8List markerBytes = byteData.buffer.asUint8List();
    final descriptor = BitmapDescriptor.fromBytes(markerBytes);

    _iconCache[cacheKey] = descriptor;
    if (_iconCache.length > _cacheCap) {
      _iconCache.remove(_iconCache.keys.first);
    }
    return descriptor;
  }
}

class ItemDetailsWidget extends StatelessWidget {
  const ItemDetailsWidget({super.key, required this.item});
  final ItemGlodal item;

  @override
  Widget build(BuildContext context) {
    final savedCubit = context.read<SavedCubit>();
    return Column(
      mainAxisSize: MainAxisSize.min,
      crossAxisAlignment: CrossAxisAlignment.start,
      mainAxisAlignment: MainAxisAlignment.start,
      children: [
        HeightSpace(16.h),
        ClipRRect(
          borderRadius: BorderRadius.circular(8),
          child: Image.network(
            item.image!,
            width: double.infinity,
            height: 160.h,
            fit: BoxFit.cover,
          ),
        ),
        HeightSpace(16.h),
        Text(item.title ?? '', style: AppTextStyles.font24SemiBold),
        Text(
          item.type ?? '',
          style: AppTextStyles.font14Bold.copyWith(color: AppColors.primary),
          maxLines: 4,
          overflow: TextOverflow.ellipsis,
        ),
        HeightSpace(8.h),
        Text(
          item.address ?? '',
          style: AppTextStyles.font14Bold.copyWith(color: AppColors.grey),
          maxLines: 4,
          overflow: TextOverflow.ellipsis,
        ),
        HeightSpace(8.h),
        Row(
          children: [
            SvgPicture.asset(AppAssets.star, width: 16.w),
            WidthSpace(4.w),
            Text(
              item.rate.toString(),
              style: AppTextStyles.font14Regular.copyWith(
                color: AppColors.yellowColor,
              ),
            ),
          ],
        ),
        Divider(height: 11.h, thickness: 0.5, color: AppColors.grey),
        Row(
          children: [
            if (item.id != null && item.type != null)
              GestureDetector(
                onTap: () {
                  savedCubit.addToSaved(
                    savedId: item.id!,
                    savedType: item.type!,
                  );
                },
                child: SvgPicture.asset(AppAssets.save, width: 36.w),
              ),
            WidthSpace(16.w),
            GestureDetector(
              onTap: () {
                final lat = item.lat;
                final long = item.long;
                if (lat != 0 && long != 0) {
                  final googleMapsUrl =
                      'https://www.google.com/maps/search/?api=1&query=$lat,$long';
                  openFileLink(googleMapsUrl);
                } else {
                  showCustomFailureToast("location_unavailable".tr());
                }
              },
              child: SvgPicture.asset(AppAssets.locationPin, width: 36.w),
            ),
            Divider(height: 11.h, thickness: 0.5, color: AppColors.grey),
          ],
        ),
        Divider(height: 24.h, thickness: 1, color: AppColors.grey),
        PrimaryButton(
          height: 48.h,

          title: "المزيد",
          onTap: () {
            String route;

            switch (item.type) {
              case 'store':
                route = RoutesKeys.kStoresDetailsItems;
                break;
              case 'place':
                route = RoutesKeys.kPlacesDetailsItems;
                break;
              case 'restaurant':
                route = RoutesKeys.kRestaurantsDetailsItems;
                break;
              case 'event':
                route = RoutesKeys.kEventsDetailsView;
                break;
              case 'story':
                route = RoutesKeys.kStoriesDetailsView;
                break;
              case 'zad':
                route = RoutesKeys.kRestaurantsDetailsItems;
                break;

              default:
                route = RoutesKeys.kPlacesDetailsItems;
            }

            push(route, context, extra: item.slug);
          },
        ),
      ],
    );
  }
}
