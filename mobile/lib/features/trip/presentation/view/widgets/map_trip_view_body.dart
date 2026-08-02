import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/managers/location_cubit/location_cubit.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/exploration/presentation/view/widgets/map_view.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_places_item_card.dart';
import 'package:hawdaj/features/trip/presentation/manager/map_trip_cubit/map_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/map_trip_cubit/map_trip_state.dart';
import 'package:url_launcher/url_launcher.dart';

class MapTripViewBody extends StatelessWidget {
  const MapTripViewBody({super.key});

  @override
  Widget build(BuildContext context) {
    final mapCubit = context.read<MapTripCubit>();

    return Scaffold(
      body: Stack(
        alignment: Alignment.bottomCenter,
        children: [
          // ===== الخريطة =====
          BlocBuilder<MapTripCubit, MapTripState>(
            buildWhen: (p, c) =>
                p.mapType != c.mapType ||
                p.markers != c.markers ||
                p.cameraTarget != c.cameraTarget ||
                p.cameraZoom != c.cameraZoom,

            builder: (context, state) {
              return GoogleMap(
                mapType: state.mapType,
                markers: state.markers,
                initialCameraPosition: CameraPosition(
                  target: state.cameraTarget,
                  zoom: state.cameraZoom,
                ),
                onMapCreated: mapCubit.onMapCreated,
                myLocationEnabled: false,
                myLocationButtonEnabled: false,
                compassEnabled: true,

                gestureRecognizers: <Factory<OneSequenceGestureRecognizer>>{
                  Factory<OneSequenceGestureRecognizer>(
                    () => EagerGestureRecognizer(),
                  ),
                },

                zoomGesturesEnabled: true,
                scrollGesturesEnabled: true,
                rotateGesturesEnabled: true,
                tiltGesturesEnabled: true,

                padding: EdgeInsets.only(
                  top: 100,
                  bottom: context.read<MapTripCubit>().state.listVisible
                      ? 180
                      : 0, //
                  left: 0,
                  right: 0,
                ),
              );
            },
          ),

          // ===== أزرار التحكم =====
          Positioned(
            bottom: 200.h,
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
              ],
            ),
          ),

          // ===== الهيدر + فلاتر الأيام (دائمًا ظاهرين) =====
          Positioned.fill(
            top: 0,
            child: SafeArea(
              bottom: false,
              child: Align(
                alignment: Alignment.topCenter,
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    // العنوان والرجوع
                    Padding(
                      padding: EdgeInsets.symmetric(horizontal: 16.w),
                      child: Row(
                        children: [
                          GestureDetector(
                            onTap: () => Navigator.of(context).maybePop(),
                            child: SvgPicture.asset(AppAssets.arrow),
                          ),
                          WidthSpace(10.w),
                          Expanded(
                            child: Center(
                              child: Text(
                                "my_trips".tr(),
                                style: AppTextStyles.font20Bold,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                    HeightSpace(8.h),

                    BlocBuilder<MapTripCubit, MapTripState>(
                      buildWhen: (p, c) =>
                          p.dayGroups != c.dayGroups ||
                          p.selectedDay != c.selectedDay,
                      builder: (context, state) {
                        final groups =
                            state.dayGroups ??
                            const <List<UnifiedPlaceModel>>[];
                        final selected = state.selectedDay; // null => الكل
                        if (groups.isEmpty) return const SizedBox.shrink();

                        return SingleChildScrollView(
                          scrollDirection: Axis.horizontal,
                          padding: EdgeInsets.symmetric(horizontal: 16.w),
                          child: Row(
                            children: [
                              ChoiceChip(
                                label: Text('filter_all'.tr()),
                                selected: selected == null,
                                onSelected: (_) => context
                                    .read<MapTripCubit>()
                                    .clearDayFilter(),
                              ),
                              WidthSpace(8.w),
                              for (int d = 0; d < groups.length; d++) ...[
                                ChoiceChip(
                                  label: Text(
                                    '${"day_singular".tr()} ${d + 1} (${groups[d].length})',
                                  ),
                                  selected: selected == d + 1,
                                  onSelected: (_) => context
                                      .read<MapTripCubit>()
                                      .showDay(d + 1),
                                ),
                                WidthSpace(8.w),
                              ],
                            ],
                          ),
                        );
                      },
                    ),
                    HeightSpace(8.h),
                  ],
                ),
              ),
            ),
          ),

          // ===== ليست أفقية عادية بأسفل الخريطة (يظهر/يخفي بالزر) =====
          BlocBuilder<MapTripCubit, MapTripState>(
            buildWhen: (p, c) =>
                p.listVisible != c.listVisible ||
                p.dayGroups != c.dayGroups ||
                p.selectedDay != c.selectedDay,
            builder: (context, state) {
              if (!state.listVisible) return const SizedBox.shrink();

              final groups =
                  state.dayGroups ?? const <List<UnifiedPlaceModel>>[];
              final selected = state.selectedDay;

              // العناصر المرئية: كل الأيام أو يوم محدد
              final List<UnifiedPlaceModel> items = (selected == null)
                  ? groups.expand((e) => e).toList()
                  : (selected - 1 >= 0 && selected - 1 < groups.length)
                  ? groups[selected - 1]
                  : const <UnifiedPlaceModel>[];

              if (items.isEmpty) {
                return Positioned(
                  left: 0,
                  right: 0,
                  bottom: 12.h,
                  child: Center(
                    child: Container(
                      padding: EdgeInsets.symmetric(
                        horizontal: 12.w,
                        vertical: 8.h,
                      ),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12.r),
                        boxShadow: const [
                          BoxShadow(blurRadius: 10, color: Colors.black12),
                        ],
                      ),
                      child: const Text('لا توجد أماكن لعرضها'),
                    ),
                  ),
                );
              }

              return Positioned(
                left: 0,
                right: 0,
                bottom: 12.h,
                child: SafeArea(
                  top: false,
                  child: SizedBox(
                    height: 170.h,
                    child: ListView.separated(
                      padding: EdgeInsets.symmetric(horizontal: 16.w),
                      scrollDirection: Axis.horizontal,
                      physics: const BouncingScrollPhysics(),
                      itemCount: items.length,
                      separatorBuilder: (_, __) => WidthSpace(10.w),
                      itemBuilder: (context, i) {
                        final item = items[i];
                        return SizedBox(
                          width: 289.w,
                          child: TasneefPlacesItemCard(
                            isFavorite: false,
                            place: item,
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
                                  print("❌ لا يمكن فتح خرائط جوجل");
                                }
                              },
                              child: SvgPicture.asset(AppAssets.locationPin),
                            ),
                          ),
                        );
                      },
                    ),
                  ),
                ),
              );
            },
          ),
        ],
      ),
    );
  }

  Future<void> handleLocationAccess(BuildContext context) async {
    final locationCubit = context.read<LocationCubit>();
    final mapCubit = context.read<MapTripCubit>();

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
            CameraPosition(target: latLng, zoom: 14.5),
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
