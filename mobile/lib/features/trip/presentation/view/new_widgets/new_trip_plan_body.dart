import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/empty_state_widget.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/places/presentation/view/widgets/places_details_items_body.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/data/model/new_trip_place_mapper.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_show_cubit/prepare_trip_show_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/reprepare_trip_cubit/reprepare_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/new_widgets/trip_day_card.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_calender.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_details_card.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_program_header.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';

class NewTripPlanBody extends StatefulWidget {
  const NewTripPlanBody({super.key, required this.model});
  final EnhancedTripData model;

  @override
  State<NewTripPlanBody> createState() => _NewTripPlanBodyState();
}

class _NewTripPlanBodyState extends State<NewTripPlanBody> {
  late EnhancedTripData currentModel;

  @override
  void initState() {
    super.initState();
    currentModel = widget.model;
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<ReprepareTripCubit, ReprepareTripState>(
      listener: (context, state) {
        if (state is ReprepareTripFailure) {
          showCustomFailureToast(state.message);
        } else if (state is ReprepareTripSuccess && state.trip.data != null) {
          showCustomSuccessToast(state.trip.message);
          setState(() {
            currentModel = state.trip.data!;
          });
          // ✅ حدث كمان البيانات داخل PrepareTripShowCubit
          context.read<PrepareTripShowCubit>().emit(
            PrepareTripShowSuccess(state.trip),
          );
        }
      },
      builder: (context, state) {
        final isLoading = state is ReprepareTripLoading;

        // 👇 أضف هنا BlocBuilder للـ PrepareTripShowCubit
        return BlocBuilder<PrepareTripShowCubit, PrepareTripShowState>(
          builder: (context, tripState) {
            EnhancedTripData model = currentModel;

            if (tripState is PrepareTripShowSuccess &&
                tripState.response.data != null) {
              model = tripState.response.data!;
            }

            return Stack(
              children: [
                CustomScrollView(
                  slivers: [
                    SliverToBoxAdapter(
                      child: TripStartAppBar(title: 'trip_program'.tr()),
                    ),
                    SliverToBoxAdapter(
                      child: TripDetailsCard(
                        iconOnePath: AppAssets.refresh,
                        iconTwoPath: AppAssets.calendarSvg,
                        startDate: formatArabicDate(model.startDate),
                        endDate: formatArabicDate(model.endDate),
                        region1: model.startRegion?.name ?? "",
                        region2: model.endRegion?.name ?? "",
                        buttonTwoColor: AppColors.primaryLight2,
                        buttonTwoTextColor: AppColors.primary,
                        buttonOne: 'refresh'.tr(),
                        buttonTwo: 'calendar'.tr(),
                        onButtonOnePressed: () {
                          context.read<ReprepareTripCubit>().reprepareTrip(
                            model.token,
                          );
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
                          final dailyPlaces = model.enhancedData.map((day) {
                            return day.allPlaces.map((place) {
                              return newTripPlaceFromEnhancedPlace(place);
                            }).toList();
                          }).toList();

                          push(
                            RoutesKeys.kMapTripView,
                            context,
                            extra: dailyPlaces,
                          );
                        },
                      ),
                    ),
                    if (model.enhancedData.isEmpty)
                      SliverFillRemaining(
                        hasScrollBody: false,
                        child: EmptyStateWidget(
                          message: 'no_places'.tr(),
                          iconPath: AppAssets.notfoundlandmark,
                          subMessage: "change_start_end_region".tr(),
                        ),
                      )
                    else
                      SliverList(
                        delegate: SliverChildBuilderDelegate((context, index) {
                          final day = model.enhancedData[index];
                          return TripDayCard(
                            model: day,
                            dayIndex: index,
                            showDelete: true,
                          );
                        }, childCount: model.enhancedData.length),
                      ),
                  ],
                ),
                if (isLoading)
                  Container(
                    color: Colors.black.withOpacity(0.3),
                    child: Center(
                      child: CircularProgressIndicator(
                        color: AppColors.primary,
                      ),
                    ),
                  ),
              ],
            );
          },
        );
      },
    );
  }
}
