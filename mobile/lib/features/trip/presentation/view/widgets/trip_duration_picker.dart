import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/custom_date_picker.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/custom_widgets_head_trip.dart';

import 'package:flutter_bloc/flutter_bloc.dart';

import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_wizard/prepare_trip_wizard_cubit.dart';

class TripDurationPicker extends StatelessWidget {
  const TripDurationPicker({super.key});

  @override
  Widget build(BuildContext context) {
    final cubit = context.watch<PrepareTripWizardCubit>();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        CustomWidgetsHeadTrip(
          title: 'trip_duration_title'.tr(),
          showButton: false,
          subtitleText: "trip_duration_subtitle".tr(),
        ),
        HeightSpace(24.h),

        Text(
          "trip_start_label".tr(),
          style: AppTextStyles.font14Regular.copyWith(
            color: AppColors.obsidianBlack,
          ),
        ),
        HeightSpace(8.h),
        CustomDatePicker(
          hint: cubit.startDateStr ?? "trip_start_label".tr(),
          formatPattern: 'yyyy/MM/dd',
          firstDate: DateTime.now(),
          lastDate: DateTime.now().add(const Duration(days: 365)),
          initialDate: cubit.startDate ?? DateTime.now(),
          onDatePicked: (date) {
            if (date != null) cubit.setStartDate(date);
          },
        ),

        HeightSpace(24.h),

        Text(
          "trip_end_label".tr(),
          style: AppTextStyles.font14Regular.copyWith(
            color: AppColors.obsidianBlack,
          ),
        ),
        HeightSpace(8.h),
        CustomDatePicker(
          hint: cubit.endDateStr ?? "trip_end_label".tr(),
          formatPattern: 'yyyy/MM/dd',
          firstDate: cubit.startDate ?? DateTime.now(),
          lastDate: DateTime.now().add(const Duration(days: 365)),
          initialDate: cubit.endDate ?? (cubit.startDate ?? DateTime.now()),
          onDatePicked: (date) {
            if (date != null) {
              if (cubit.startDate != null && date.isBefore(cubit.startDate!)) {
                showCustomFailureToast("end_date_after_start".tr());
              } else {
                cubit.setEndDate(date);
              }
            }
          },
        ),
      ],
    );
  }
}
