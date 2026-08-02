import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/managers/location_cubit/location_cubit.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/exploration/data/model/glodal_search/item.dart';
import 'package:hawdaj/features/exploration/presentation/manager/get_search_global_cubit/get_search_global_cubit.dart';
import 'package:hawdaj/features/exploration/presentation/manager/map_cubit/map_cubit.dart';
import 'package:hawdaj/features/exploration/presentation/view/widgets/map_item_glodal_to_unified_place.dart';
import 'package:hawdaj/features/exploration/presentation/view/widgets/map_view.dart';
import 'package:hawdaj/features/exploration/presentation/view/widgets/multi_select_pills_bar.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_places_item_card.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';
import 'package:url_launcher/url_launcher.dart';

class MapView extends StatefulWidget {
  const MapView({super.key});

  @override
  State<MapView> createState() => _MapViewState();
}

class _MapViewState extends State<MapView> {
  @override
  bool _movedToUserLocation = false;

  @override
  void initState() {
    super.initState();

    final cubit = context.read<GetSearchGlobalCubit>();
    final mapCubit = context.read<MapCubit>();
    final locationCubit = context.read<LocationCubit>();

    // نحاول نجيب الموقع لو مش موجود
    if (locationCubit.state is! LocationLoaded) {
      locationCubit.fetchCurrentLocation().then((_) async {
        final state = locationCubit.state;
        if (state is LocationLoaded && !_movedToUserLocation) {
          _movedToUserLocation = true;

          final latLng = LatLng(
            state.position.latitude,
            state.position.longitude,
          );

          final controller = await mapCubit.mapController.future;
          controller.animateCamera(
            CameraUpdate.newCameraPosition(
              CameraPosition(target: latLng, zoom: 6),
            ),
          );
          mapCubit.addUserLocationMarker(latLng);
        }
      });
    } else {
      // لو موجود بالفعل
      final state = locationCubit.state;
      if (state is LocationLoaded && !_movedToUserLocation) {
        _movedToUserLocation = true;

        Future.microtask(() async {
          final latLng = LatLng(
            state.position.latitude,
            state.position.longitude,
          );

          final controller = await mapCubit.mapController.future;
          controller.animateCamera(
            CameraUpdate.newCameraPosition(
              CameraPosition(target: latLng, zoom: 6),
            ),
          );
          mapCubit.addUserLocationMarker(latLng);
        });
      }
    }

    cubit.pagingController.addListener(() async {
      final items = cubit.pagingController.itemList;

      if (items != null && items.isNotEmpty) {
        mapCubit.setMarkersFromItems(items);
        print('📍 Loaded markers count: ${items.length}');
      } else {
        mapCubit.clearMarkers();
      }
    });
  }

  @override
  void dispose() {
    context.read<MapCubit>().clearMarkers();
    context.read<GetSearchGlobalCubit>().pagingController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<GetSearchGlobalCubit>();
    final mapCubit = context.watch<MapCubit>();

    return Scaffold(
      body: Stack(
        alignment: Alignment.bottomCenter,
        children: [
          GoogleMap(
            markers: mapCubit.markers,
            initialCameraPosition: const CameraPosition(
              target: LatLng(24.0, 43.0),
              zoom: 6,
            ),
            mapType: mapCubit.mapType,
            onMapCreated: (controller) {
              if (!mapCubit.mapController.isCompleted) {
                mapCubit.mapController.complete(controller);
              }
            },
          ),
          Positioned(
            bottom: 250.h,
            right: 16,
            child: Column(
              children: [
                Container(
                  //   padding: const EdgeInsets.all(9),
                  decoration: ShapeDecoration(
                    color: const Color(0xB2F2EBF6),
                    shape: RoundedRectangleBorder(
                      side: BorderSide(
                        width: 1.50,
                        color: Colors.white.withValues(alpha: 0.35),
                      ),
                      borderRadius: BorderRadius.circular(10),
                    ),
                  ),
                  child: TinyIconButton(
                    tooltip: 'موقعي الحالي',
                    icon: AppAssets.pin2, // أو أيقونة أخرى تعجبك
                    onPressed: () => handleLocationAccess(context),
                  ),
                ),

                HeightSpace(8.h),
                TinyIconButton(
                  tooltip: 'Normal / Satellite',
                  icon: AppAssets.moon,
                  onPressed: mapCubit.toggleMapTypeGroup1,
                ),
                HeightSpace(8.h),
                TinyIconButton(
                  tooltip: 'Terrain / Hybrid',
                  icon: AppAssets.mapMap,
                  onPressed: mapCubit.toggleMapTypeGroup2,
                ),
                HeightSpace(8.h),
                TinyIconButton(
                  tooltip: 'Toggle List',
                  icon: AppAssets.maxmini,
                  onPressed: mapCubit.toggleListSearchVisibility,
                ),
                HeightSpace(8.h),
              ],
            ),
          ),

          Positioned.fill(
            top: 0,
            child: SafeArea(
              bottom: false,
              child: Align(
                alignment: Alignment.topCenter,
                child: Column(
                  children: [
                    Row(
                      //mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      mainAxisSize: MainAxisSize.max,
                      children: [
                        //       SvgPicture.asset(AppAssets.arrow),
                        WidthSpace(10.w),
                        Expanded(
                          child: Center(
                            child: Text(
                              'discover'.tr(),
                              style: AppTextStyles.font20Bold,
                            ),
                          ),
                        ),
                      ],
                    ),
                    HeightSpace(8.h),

                    Padding(
                      padding: const EdgeInsets.all(8.0),
                      child: CustomTextField(
                        controller: mapCubit.searchController,
                        height: 40.h,
                        hint: 'search_anything'.tr(),
                        onChange: (v) {
                          mapCubit.updateSearch();
                        },
                        leadingIconPath: AppAssets.searchNormal,
                      ),
                    ),
                    HeightSpace(8.h),
                    const MultiSelectPillsBar(),
                  ],
                ),
              ),
            ),
          ),
          if (mapCubit.isListSearchVisible)
            SizedBox(
              height: 200.h,
              child: PagedListView<int, ItemGlodal>.separated(
                pagingController: cubit.pagingController,
                scrollDirection: Axis.horizontal,
                builderDelegate: PagedChildBuilderDelegate<ItemGlodal>(
                  itemBuilder: (context, item, index) {
                    final placeModel = mapItemGlodalToUnifiedPlace(item);
                    return SizedBox(
                      width: 289.w,
                      child: TasneefPlacesItemCard(
                        isFavorite: false,
                        place: placeModel,
                        goToDetails: () {
                          String route;
                          dynamic extra;

                          switch (item.type) {
                            case 'store':
                              route = RoutesKeys.kStoresDetailsItems;
                              extra = item.slug;
                              break;
                            case 'place':
                              route = RoutesKeys.kPlacesDetailsItems;
                              extra = item.slug;
                              break;
                            case 'restaurant':
                              route = RoutesKeys.kRestaurantsDetailsItems;
                              extra = {'slug': item.slug, 'id': item.id};
                              break;
                            case 'event':
                              route = RoutesKeys.kEventsDetailsView;
                              extra = item.slug;
                              break;
                            case 'story':
                              route = RoutesKeys.kStoriesDetailsView;
                              extra = item.slug;
                              break;
                            case 'zad':
                              route = RoutesKeys.kRestaurantsDetailsItems;
                              extra = {'slug': item.slug, 'id': item.id};
                              break;
                            default:
                              route = RoutesKeys.kPlacesDetailsItems;
                              extra = item.slug;
                          }

                          push(route, context, extra: extra);
                        },

                        child: GestureDetector(
                          onTap: () async {
                            final lat = item.lat;
                            final long = item.long;

                            final url = Uri.parse(
                              "https://www.google.com/maps/search/?api=1&query=$lat,$long",
                            );

                            if (await canLaunchUrl(url)) {
                              await launchUrl(
                                url,
                                mode: LaunchMode.externalApplication,
                              );
                            } else {
                              print("❌ ${"google_maps_error".tr()}");
                            }
                          },
                          child: SvgPicture.asset(AppAssets.locationPin),
                        ),
                      ),
                    );
                  },
                  firstPageErrorIndicatorBuilder: (context) =>
                      const Center(child: Text("حدث خطأ في تحميل العروض")),
                  noItemsFoundIndicatorBuilder: (context) => Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        TextButton(
                          onPressed: cubit.refresh,
                          child: const Text("اعادة المحاولة"),
                        ),
                      ],
                    ),
                  ),
                  newPageProgressIndicatorBuilder: (context) =>
                      const Center(child: CircularProgressIndicator()),
                  firstPageProgressIndicatorBuilder: (context) =>
                      const Center(child: CircularProgressIndicator()),
                ),
                separatorBuilder: (context, index) => SizedBox(width: 12.w),
              ),
            ),
        ],
      ),
    );
  }

  Future<void> handleLocationAccess(BuildContext context) async {
    final locationCubit = context.read<LocationCubit>();
    final mapCubit = context.read<MapCubit>();

    // 1️⃣ تحقق من حالة الإذن
    LocationPermission permission = await Geolocator.checkPermission();

    // 2️⃣ إذا كان الإذن مرفوض دائماً، وجه للإعدادات
    if (permission == LocationPermission.deniedForever) {
      final bool openedSettings = await Geolocator.openAppSettings();

      if (!openedSettings) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('please_enable_location_from_settings'.tr()),
            backgroundColor: Colors.red,
            action: SnackBarAction(
              label: 'settings'.tr(),
              textColor: Colors.white,
              onPressed: () => Geolocator.openAppSettings(),
            ),
          ),
        );
      }
      return;
    }

    // 3️⃣ إذا كان الإذن مرفوض، اطلبه
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();

      // إذا رفض بعد الطلب
      if (permission == LocationPermission.denied) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('location_permission_denied'.tr()),
            backgroundColor: Colors.red,
          ),
        );
        return;
      }

      // إذا رفض دائماً بعد الطلب
      if (permission == LocationPermission.deniedForever) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('location_permission_denied'.tr()),
            backgroundColor: Colors.red,
            action: SnackBarAction(
              label: 'settings'.tr(),
              textColor: Colors.white,
              backgroundColor: AppColors.primary,
              onPressed: () => Geolocator.openAppSettings(),
            ),
          ),
        );
        return;
      }
    }

    // 4️⃣ الإذن ممنوح، احصل على الموقع
    if (permission == LocationPermission.always ||
        permission == LocationPermission.whileInUse) {
      await locationCubit.fetchCurrentLocation();

      final state = locationCubit.state;

      if (state is LocationLoaded) {
        final latLng = LatLng(
          state.position.latitude,
          state.position.longitude,
        );

        final controller = await mapCubit.mapController.future;
        controller.animateCamera(
          CameraUpdate.newCameraPosition(
            CameraPosition(target: latLng, zoom: 6),
          ),
        );
        mapCubit.addUserLocationMarker(latLng);
      } else if (state is LocationError) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(state.message), backgroundColor: Colors.red),
        );
      }
    }
  }
}
