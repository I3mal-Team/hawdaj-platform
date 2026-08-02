import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/empty_state_widget.dart';

import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_places_item_card.dart';

import 'package:hawdaj/features/trip/data/model/trip_model/prepare_trip_data.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/trip_model.dart';
import 'package:hawdaj/features/trip/presentation/manager/finish_trip_details_cubit/finish_trip_details_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/trip_plan_cubit/trip_plan_cubit.dart';

import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_calender.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_dealt.dart';

import 'package:hawdaj/features/trip/presentation/view/widgets/trip_day_row.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_details_card.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_program_header.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';
import 'package:url_launcher/url_launcher.dart';

class TripPlanBody extends StatelessWidget {
  const TripPlanBody({super.key, required this.model, required this.tripModel});

  final TripData model;
  final TripModel tripModel;

  @override
  Widget build(BuildContext context) {
    final planState = context.watch<TripPlanCubit>().state;
    final days = planState.days;
    final dailyPlaces = planState.dailyPlaces;
    final allDaysEmpty = dailyPlaces.every((d) => d.isEmpty);

    final nonEmptyDaysCount = dailyPlaces.where((d) => d.isNotEmpty).length;
    final visibleDayIndexes = List<int>.generate(days.length, (i) => i)
        .where((i) => i < dailyPlaces.length && dailyPlaces[i].isNotEmpty)
        .toList();
    return CustomScrollView(
      slivers: [
        SliverToBoxAdapter(child: TripStartAppBar(title: 'trip_program'.tr())),
        SliverToBoxAdapter(
          child: TripDetailsCard(
            iconOnePath: AppAssets.refresh,
            iconTwoPath: AppAssets.calendarSvg,

            startDate: formatArabicDate(
              parseDate(model.startDate) ?? DateTime.now(),
            ),
            endDate: formatArabicDate(
              parseDate(model.endDate) ??
                  (parseDate(
                        model.startDate,
                      )?.add(Duration(days: int.tryParse(model.days) ?? 0)) ??
                      DateTime.now()),
            ),
            region1: model.region1Object.name.toString(),
            region2: model.region2Object.name.toString(),
            buttonTwoColor: AppColors.primaryLight2,
            buttonTwoTextColor: AppColors.primary,
            buttonOne: 'refresh'.tr(),
            buttonTwo: 'calendar'.tr(),
            onButtonOnePressed: () {
              context.read<FinishTripDetailsCubit>().finishTripDetails();
            },
            onButtonTwoPressed: () {
              baseBottomSheet(
                title: 'add_to_calendar'.tr(),
                child: BottomSheetTripCalender(),
                context: context,
                hideNavBar: false,
              );
            },
          ),
        ),

        SliverToBoxAdapter(
          child: TripProgramHeader(
            onTap: () {
              push(RoutesKeys.kMapTripView, context, extra: dailyPlaces);
            },
          ),
        ),
        if (allDaysEmpty)
          SliverToBoxAdapter(
            child: EmptyStateWidget(
              message: 'no_places'.tr(),
              iconPath: AppAssets.notfoundlandmark,
              subMessage: "change_start_end_region".tr(),
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
              if (placesOfDay.isEmpty) {
                return const SizedBox.shrink();
              }

              return Padding(
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
                        separatorBuilder: (_, __) => WidthSpace(10.w),
                        itemBuilder: (context, i) {
                          final item = placesOfDay[i];
                          final isOnlyItemInDay = placesOfDay.length == 1;

                          // بدقّة أكتر: هل العنصر الحالي هو الوحيد (يعني i == 0 وبرضه length == 1)؟
                          final isExactlyThisOnly =
                              placesOfDay.length == 1 && i == 0;

                          return SizedBox(
                            width: 220.w,
                            child: TasneefPlacesItemCard(
                              manor: false,
                              place: item,
                              isFavorite: false,
                              onTapFavorite: () {},
                              icon2: isExactlyThisOnly
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
                                            message: "delete_place_message"
                                                .tr(),
                                            onTapConfirm: () {
                                              context
                                                  .read<TripPlanCubit>()
                                                  .removePlace(index, i);
                                              Navigator.of(context).maybePop();
                                            },
                                            onTapCancel: () {
                                              Navigator.pop(context);
                                            },
                                          ),
                                        );
                                      },
                                      child: SvgPicture.asset(
                                        AppAssets.trashIcon,
                                      ),
                                    ),
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
}

List<_TripDayVM> buildDaysFromDates(String? start, String? end) {
  final startDt = parseDate(start);
  final endDt = parseDate(end);

  if (startDt == null) {
    return [
      _TripDayVM(
        index: 1,
        displayDate: (start?.trim().isNotEmpty ?? false) ? start!.trim() : '—',
      ),
    ];
  }

  if (endDt == null || !endDt.isAfter(startDt)) {
    return [_TripDayVM(index: 1, displayDate: formatArabicDate(startDt))];
  }

  final result = <_TripDayVM>[];
  var cursor = DateTime(startDt.year, startDt.month, startDt.day);
  var i = 1;
  while (!cursor.isAfter(endDt)) {
    result.add(_TripDayVM(index: i++, displayDate: formatArabicDate(cursor)));
    cursor = cursor.add(const Duration(days: 1));
  }
  return result;
}

String formatArabicDate(DateTime date) {
  const months = [
    'يناير',
    'فبراير',
    'مارس',
    'أبريل',
    'مايو',
    'يونيو',
    'يوليو',
    'أغسطس',
    'سبتمبر',
    'أكتوبر',
    'نوفمبر',
    'ديسمبر',
  ];
  return '${date.day} ${months[date.month - 1]} ${date.year}';
}

class _TripDayVM {
  final int index;
  final String displayDate;
  _TripDayVM({required this.index, required this.displayDate});
}

DateTime? parseDate(String? value) {
  if (value == null || value.trim().isEmpty) return null;

  final iso = DateTime.tryParse(value);
  if (iso != null) return iso;

  // توقع "yyyy/MM/dd" من الـ response
  final partsSlash = value.split('/');
  if (partsSlash.length == 3) {
    final y = int.tryParse(partsSlash[0]);
    final m = int.tryParse(partsSlash[1]);
    final d = int.tryParse(partsSlash[2]);
    if (y != null && m != null && d != null) return DateTime(y, m, d);
  }

  // dd-MM-yyyy أو dd/MM/yyyy
  final partsDash = value.split('-');
  if (partsDash.length == 3) {
    final d = int.tryParse(partsDash[0]);
    final m = int.tryParse(partsDash[1]);
    final y = int.tryParse(partsDash[2]);
    if (d != null && m != null && y != null) return DateTime(y, m, d);
  }
  return null;
}

List<List<UnifiedPlaceModel>> extractDailyPlaces(dynamic raw) {
  if (raw == null) return const <List<UnifiedPlaceModel>>[];

  if (raw is List) {
    // نتوقع أن كل عنصر يمثل يوماً
    return raw.map<List<UnifiedPlaceModel>>((dayRaw) {
      if (dayRaw is List) {
        // عناصر اليوم قد تكون UnifiedPlaceModel أو Map/int
        return dayRaw.map<UnifiedPlaceModel>((e) {
          if (e is UnifiedPlaceModel) return e; // لا تعِد التحويل
          return UnifiedPlaceModel.fromJson(e);
        }).toList();
      }
      // لو رجع عنصر واحد فقط لهذا اليوم
      if (dayRaw is UnifiedPlaceModel) return <UnifiedPlaceModel>[dayRaw];
      return <UnifiedPlaceModel>[UnifiedPlaceModel.fromJson(dayRaw)];
    }).toList();
  }

  return const <List<UnifiedPlaceModel>>[];
}
