import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/should_execute.dart';

import 'package:hawdaj/features/trip/data/model/trip_model/trip_model.dart';
import 'package:hawdaj/features/trip/presentation/manager/finish_trip_details_cubit/finish_trip_details_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_cubit/save_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_to_email_cubit/save_trip_to_email_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/trip_plan_cubit/trip_plan_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/save_trip_to_email_view.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/save_trip_view.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_plan_body.dart';

class TripPlan extends StatelessWidget {
  const TripPlan({super.key, required this.tripModel});
  final TripModel tripModel;

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<FinishTripDetailsCubit, FinishTripDetailsState>(
      builder: (context, state) {
        if (state is FinishTripDetailsLoading) {
          return const Scaffold(
            body: Center(child: CircularProgressIndicator()),
          );
        }

        if (state is FinishTripDetailsError) {
          return Scaffold(
            body: Center(
              child: Padding(
                padding: const EdgeInsets.all(8.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text('حدث خطأ'),
                    HeightSpace(16.h),
                    Text('يرجى المحاولة لاحقًا'),
                    HeightSpace(16.h),
                    PrimaryButton(
                      title: 'محاولة مجددة',
                      onTap: () {
                        context
                            .read<FinishTripDetailsCubit>()
                            .finishTripDetails();
                      },
                    ),
                  ],
                ),
              ),
            ),
          );
        }

        if (state is FinishTripDetailsSuccess) {
          return BlocProvider(
            create: (_) => TripPlanCubit(state.trip, tripModel),
            child: Builder(
              // ← مهم
              builder: (ctx) {
                return Scaffold(
                  body: TripPlanBody(model: state.trip, tripModel: tripModel),

                  bottomNavigationBar: Padding(
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
                            child: PrimaryButton(
                              padding: EdgeInsets.zero,
                              title: 'save'.tr(),
                              iconColor: AppColors.primary,
                              onTap: () {
                                final saveTrip = ctx.read<SaveTripCubit>();
                                final updatedItems = ctx
                                    .read<TripPlanCubit>()
                                    .mapDailyPlacesToIds();

                                shouldExecute(
                                  context: context,
                                  callback: () {
                                    baseBottomSheet(
                                      context: ctx,
                                      hideNavBar: false,
                                      child: BlocProvider.value(
                                        value: saveTrip,
                                        child: SaveTripView(
                                          tripModel: tripModel,
                                          model: state.trip,
                                          itemsOverride: updatedItems,
                                        ),
                                      ),
                                    );
                                  },
                                );
                              },
                            ),
                          ),
                          WidthSpace(8.w),

                          // ===== مشاركة =====
                          Expanded(
                            child: PrimaryButton(
                              padding: EdgeInsets.zero,
                              title: "share".tr(),
                              iconColor: AppColors.primary,
                              backgroundColor: AppColors.primaryLight2,
                              textColor: AppColors.primary,
                              onTap: () {
                                final emailCubit = ctx
                                    .read<SaveTripToEmailCubit>(); // استخدم ctx
                                final updatedItems = ctx
                                    .read<TripPlanCubit>()
                                    .mapDailyPlacesToIds();

                                baseBottomSheet(
                                  context: ctx, // مهمة جدًا
                                  hideNavBar: false,
                                  child: BlocProvider.value(
                                    value: emailCubit,
                                    child: SaveTripToEmailView(
                                      date: tripModel.date ?? '',
                                      days: tripModel.days.toString(),
                                      endDate: tripModel.endDate ?? '',
                                      itemPerDay: tripModel.funnyPlacePerDay
                                          .toString(),
                                      items: updatedItems,
                                      region1: tripModel.region1 ?? '',
                                      region2: tripModel.region2 ?? '',
                                      startDate: tripModel.startDate ?? '',
                                    ),
                                  ),
                                );
                              },
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              },
            ),
          );
        }

        return const SizedBox.shrink();
      },
    );
  }
}
