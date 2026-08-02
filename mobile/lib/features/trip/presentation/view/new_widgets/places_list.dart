import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_places_item_card.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/data/model/new_trip_place_mapper.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_show_cubit/prepare_trip_show_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_dealt.dart';
import 'package:url_launcher/url_launcher.dart';

class PlacesList extends StatelessWidget {
  const PlacesList({
    super.key,
    required this.places,
    required this.period,
    required this.dayIndex,
    required this.showDelete,
  });
  final List<Place> places;

  final String period;
  final int dayIndex;
  final bool showDelete;

  @override
  Widget build(BuildContext context) {
    return ListView.separated(
      physics: const NeverScrollableScrollPhysics(),
      shrinkWrap: true,
      itemCount: places.length,
      itemBuilder: (context, index) {
        final place = places[index];
        final unifiedPlace = newTripPlaceFromEnhancedPlace(place);

        return TasneefPlacesItemCard(
          place: unifiedPlace,
          isFavorite: false,

          onTapFavorite: () {},

          icon2: showDelete
              ? !context.read<PrepareTripShowCubit>().canDeletePlace(
                      dayIndex: dayIndex,
                      period: period,
                    )
                    ? null
                    : GestureDetector(
                        onTap: () {
                          baseBottomSheet(
                            context: context,
                            hideNavBar: false,
                            title: 'delete_place'.tr(),
                            child: BottomSheetTripDealt(
                              cancel: 'cancel'.tr(),
                              confirm: 'confirm'.tr(),
                              title: "delete_place_confirm".tr(),
                              message: "delete_place_message".tr(),
                              onTapConfirm: () {
                                final currentIndex = places.indexOf(place);

                                print(
                                  '🗑 حذف مكان من اليوم $dayIndex - الفترة $period - الفهرس $currentIndex',
                                );

                                context
                                    .read<PrepareTripShowCubit>()
                                    .removePlaceFromDay(
                                      dayIndex: dayIndex,
                                      period: period,
                                      placeIndex: currentIndex,
                                    );
                                Navigator.pop(context);
                              },
                              onTapCancel: () {
                                Navigator.pop(context);
                              },
                            ),
                          );
                        },
                        child: SvgPicture.asset(AppAssets.trashIcon),
                      )
              : SizedBox.fromSize(),
          goToDetails: () {
            String route;
            dynamic extra;

            switch (unifiedPlace.type) {
              case 'store':
                route = RoutesKeys.kStoresDetailsItems;
                extra = unifiedPlace.slug;
                break;
              case 'place':
                route = RoutesKeys.kPlacesDetailsItems;
                extra = unifiedPlace.slug;
                break;
              case 'restaurant':
                route = RoutesKeys.kRestaurantsDetailsItems;
                extra = {'slug': unifiedPlace.slug, 'id': unifiedPlace.id};
                break;
              case 'event':
                route = RoutesKeys.kEventsDetailsView;
                extra = unifiedPlace.slug;
                break;
              case 'story':
                route = RoutesKeys.kStoriesDetailsView;
                extra = unifiedPlace.slug;
                break;
              case 'zad':
                route = RoutesKeys.kRestaurantsDetailsItems;
                extra = {'slug': unifiedPlace.slug, 'id': unifiedPlace.id};
                break;
              default:
                route = RoutesKeys.kPlacesDetailsItems;
                extra = unifiedPlace.slug;
            }

            push(route, context, extra: extra);
          },

          child: GestureDetector(
            onTap: () async {
              final lat = unifiedPlace.lat;
              final long = unifiedPlace.long;

              final url = Uri.parse(
                "https://www.google.com/maps/search/?api=1&query=$lat,$long",
              );

              if (await canLaunchUrl(url)) {
                await launchUrl(url, mode: LaunchMode.externalApplication);
              } else {
                print("❌ لا يمكن فتح خرائط جوجل");
              }
            },
            child: SvgPicture.asset(AppAssets.locationPin),
          ),
        );
      },
      separatorBuilder: (context, index) => HeightSpace(12.h),
    );
  }
}
