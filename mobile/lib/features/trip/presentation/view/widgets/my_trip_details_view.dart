import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_places_item_card.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';
import 'package:hawdaj/features/trip/presentation/manager/delete_trip_cubit/delete_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/my_trip_cubit/my_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_to_email_cubit/save_trip_to_email_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/view_trip_cubit/view_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/delete_trip_bottom_sheet.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/save_trip_to_email_view.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_day_row.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_details_card.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_plan_body.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_program_header.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';
import 'package:url_launcher/url_launcher.dart';

class MyTripDetailsView extends StatelessWidget {
  const MyTripDetailsView({super.key, required this.token});
  final String token;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocBuilder<ViewTripCubit, ViewTripState>(
        builder: (context, state) {
          if (state is ViewTripLoading) {
            return const Center(child: CircularProgressIndicator());
          } else if (state is ViewTripError) {
            return Center(
              child: Column(
                children: [
                  Text(state.failure.errMessage),
                  HeightSpace(16.h),
                  ElevatedButton(
                    onPressed: () => context.read<ViewTripCubit>().viewTrip(),
                    child: Text("retry".tr()),
                  ),
                ],
              ),
            );
          } else if (state is ViewTripLoaded) {
            final trip = state.trip;
            final days = buildDaysFromDates(trip.startDate, trip.endDate);

            final dailyPlaces = extractDailyPlaces(trip.places);
            return CustomScrollView(
              slivers: [
                SliverToBoxAdapter(
                  child: TripStartAppBar(title: "trip_program".tr()),
                ),
                SliverToBoxAdapter(
                  child: TripDetailsCard(
                    iconOnePath: AppAssets.sendSquare,
                    iconTwoPath: AppAssets.trash,

                    startDate: formatArabicDate(
                      parseDate(trip.startDate) ?? DateTime.now(),
                    ),
                    endDate: formatArabicDate(
                      parseDate(trip.endDate) ?? DateTime.now(),
                    ),
                    region1: trip.region1Object.name,
                    region2: trip.region2Object.name,
                    buttonTwo: "delete".tr(),
                    buttonOne: "share".tr(),
                    onButtonOnePressed: () {
                      final emailCubit = SaveTripToEmailCubit(
                        getIt<TripRepo>(),
                      );
                      baseBottomSheet(
                        context: context,
                        hideNavBar: false,
                        child: BlocProvider.value(
                          value: emailCubit,
                          child: SaveTripToEmailView(
                            date: trip.date,
                            days: trip.days,
                            endDate: trip.endDate.toString(),
                            itemPerDay: trip.funnyPlacePerDay.toString(),
                            items: trip.trip.items,
                            region1: trip.region1.toString(),
                            region2: trip.region2.toString(),
                            startDate: trip.startDate.toString(),
                          ),
                        ),
                      );
                    },
                    onButtonTwoPressed: () async {
                      final result = await baseBottomSheet(
                        context: context,
                        hideNavBar: false,
                        child: MultiBlocProvider(
                          providers: [
                            BlocProvider(
                              create: (ctx) =>
                                  DeleteTripCubit(getIt<TripRepo>()),
                            ),
                            BlocProvider.value(
                              value: context.read<MyTripCubit>(),
                            ),
                          ],
                          child: DeleteTripBottomSheet(token: token),
                        ),
                      );

                      if (result == true) {
                        Navigator.of(context).pop(true);
                      }
                    },
                  ),
                ),
                SliverToBoxAdapter(
                  child: TripProgramHeader(
                    onTap: () {
                      push(
                        RoutesKeys.kMapTripView,
                        context,
                        extra: dailyPlaces,
                      );
                    },
                  ),
                ),

                SliverPadding(
                  padding: EdgeInsets.symmetric(horizontal: 16.w),
                  sliver: SliverList.builder(
                    itemCount: days.length,
                    itemBuilder: (context, index) {
                      final vm = days[index];
                      final placesOfDay = index < dailyPlaces.length
                          ? dailyPlaces[index]
                          : const <UnifiedPlaceModel>[];

                      return placesOfDay.isEmpty
                          ? const SizedBox()
                          : Padding(
                              padding: EdgeInsets.only(bottom: 14.h),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  TripDayRow(
                                    index: vm.index,
                                    dateText: vm.displayDate,
                                    count: placesOfDay.length,
                                  ),
                                  HeightSpace(8.h),
                                  SizedBox(
                                    height: 140.h,
                                    child: ListView.separated(
                                      scrollDirection: Axis.horizontal,
                                      physics: const BouncingScrollPhysics(),
                                      itemCount: placesOfDay.length,
                                      separatorBuilder: (_, __) =>
                                          WidthSpace(10.w),
                                      itemBuilder: (context, i) {
                                        final item = placesOfDay[i];
                                        return SizedBox(
                                          width: 220.w,

                                          child: TasneefPlacesItemCard(
                                            manor: false,
                                            place: item,
                                            isFavorite: false,
                                            onTapFavorite: () {},

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
                                                    mode: LaunchMode
                                                        .externalApplication,
                                                  );
                                                } else {
                                                  print(
                                                    "❌ لا يمكن فتح خرائط جوجل",
                                                  );
                                                }
                                              },
                                              child: SvgPicture.asset(
                                                AppAssets.locationPin,
                                              ),
                                            ),

                                            goToDetails: () {
                                              String route;
                                              dynamic extra;

                                              switch (item.type) {
                                                case 'store':
                                                  route = RoutesKeys
                                                      .kStoresDetailsItems;
                                                  extra = item.slug;
                                                  break;
                                                case 'place':
                                                  route = RoutesKeys
                                                      .kPlacesDetailsItems;
                                                  extra = item.slug;
                                                  break;
                                                case 'restaurant':
                                                  route = RoutesKeys
                                                      .kRestaurantsDetailsItems;
                                                  extra = {
                                                    'slug': item.slug,
                                                    'id': item.id,
                                                  };
                                                  break;
                                                case 'event':
                                                  route = RoutesKeys
                                                      .kEventsDetailsView;
                                                  extra = item.slug;
                                                  break;
                                                case 'story':
                                                  route = RoutesKeys
                                                      .kStoriesDetailsView;
                                                  extra = item.slug;
                                                  break;
                                                case 'zad':
                                                  route = RoutesKeys
                                                      .kRestaurantsDetailsItems;
                                                  extra = {
                                                    'slug': item.slug,
                                                    'id': item.id,
                                                  };
                                                  break;
                                                default:
                                                  route = RoutesKeys
                                                      .kPlacesDetailsItems;
                                                  extra = item.slug;
                                              }

                                              push(
                                                route,
                                                context,
                                                extra: extra,
                                              );
                                            },
                                          ),
                                        );
                                      },
                                    ),
                                  ),
                                ],
                              ),
                            );
                    },
                  ),
                ),
              ],
            );
          }

          return const SizedBox();
        },
      ),
    );
  }
}
