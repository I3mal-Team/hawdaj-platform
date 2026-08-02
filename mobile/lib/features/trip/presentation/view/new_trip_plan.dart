import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/should_execute.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_show_cubit/prepare_trip_show_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_to_email_cubit/save_trip_to_email_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_v2_cubit/save_trip_v2_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/new_widgets/new_trip_plan_body.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/new_save_trip_view_base_sheet.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/save_trip_to_email_view.dart';

class NewTripPlan extends StatelessWidget {
  const NewTripPlan({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<PrepareTripShowCubit, PrepareTripShowState>(
      builder: (context, state) {
        if (state is PrepareTripShowLoading) {
          return const Scaffold(
            body: Center(child: CircularProgressIndicator()),
          );
        }

        if (state is PrepareTripShowFailure) {
          return Scaffold(
            body: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('حدث خطأ'),
                  HeightSpace(16.h),
                  PrimaryButton(
                    title: 'محاولة مجددة',
                    onTap: () {
                      context.read<PrepareTripShowCubit>().showTrip();
                    },
                  ),
                ],
              ),
            ),
          );
        }

        if (state is PrepareTripShowSuccess) {
          final tripData = state.response.data;

          return Scaffold(
            body: NewTripPlanBody(model: tripData!),
            bottomNavigationBar: (tripData.enhancedData.isEmpty)
                ? null
                : Padding(
                    padding: EdgeInsets.symmetric(
                      horizontal: 16.w,
                      vertical: 16.h,
                    ),
                    child: SizedBox(
                      height: 40.h,
                      child: Row(
                        children: [
                          // ===== حفظ =====
                          Expanded(
                            child: Builder(
                              builder: (context) {
                                return PrimaryButton(
                                  padding: EdgeInsets.zero,
                                  title: 'save'.tr(),
                                  iconColor: AppColors.primary,
                                  onTap: () {
                                    final saveTrip = context
                                        .read<SaveTripV2Cubit>();

                                    shouldExecute(
                                      context: context,
                                      callback: () {
                                        baseBottomSheet(
                                          context: context,
                                          hideNavBar: false,
                                          child: BlocProvider.value(
                                            value: saveTrip,
                                            child: NewSaveTripV2ViewBaseSheet(
                                              tripToken: tripData.token ?? '',
                                              itemsOverride:
                                                  extractItemIdsFromEnhancedTrip(
                                                    tripData,
                                                  ),
                                            ),
                                          ),
                                        );
                                      },
                                    );
                                  },
                                );
                              },
                            ),
                          ),

                          WidthSpace(8.w),
                          // ===== مشاركة =====
                          Expanded(
                            child: Builder(
                              builder: (ctx) {
                                return PrimaryButton(
                                  padding: EdgeInsets.zero,
                                  title: "share".tr(),
                                  iconColor: AppColors.primary,
                                  backgroundColor: AppColors.primaryLight2,
                                  textColor: AppColors.primary,
                                  onTap: () {
                                    final emailCubit = ctx
                                        .read<
                                          SaveTripToEmailCubit
                                        >(); // ✅ استخدم context الصحيح

                                    baseBottomSheet(
                                      context: ctx,
                                      hideNavBar: false,
                                      child: BlocProvider.value(
                                        value: emailCubit,
                                        child: SaveTripToEmailView(
                                          date: tripData.startDate,
                                          days: tripData.totalDays.toString(),
                                          endDate: tripData.endDate,
                                          itemPerDay: tripData.placesPerDay
                                              .toString(),
                                          items: extractItemIdsFromEnhancedTrip(
                                            tripData,
                                          ),
                                          region1:
                                              tripData.startRegion?.id
                                                  .toString() ??
                                              '',
                                          region2:
                                              tripData.endRegion?.id
                                                  .toString() ??
                                              '',
                                          startDate: tripData.startDate,
                                        ),
                                      ),
                                    );
                                  },
                                );
                              },
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
          );
        }

        return const SizedBox.shrink();
      },
    );
  }
}

List<List<int>> extractItemIdsFromEnhancedTrip(EnhancedTripData data) {
  final List<List<int>> items = [];

  for (var day in data.enhancedData) {
    final morningIds =
        day.morning?.places
            .where((e) => e.id != null)
            .map((e) => e.id!)
            .toList() ??
        [];

    final eveningIds =
        day.evening?.places
            .where((e) => e.id != null)
            .map((e) => e.id!)
            .toList() ??
        [];

    final combined = [...morningIds, ...eveningIds];

    if (combined.isNotEmpty) {
      items.add(combined);
    }
  }

  return items;
}
