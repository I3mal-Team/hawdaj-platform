import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';

import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_wizard/prepare_trip_wizard_cubit.dart';

class PlacesCountContainer extends StatelessWidget {
  const PlacesCountContainer({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<PrepareTripWizardCubit, PrepareTripWizardState>(
      builder: (context, state) {
        final cubit = context.read<PrepareTripWizardCubit>();
        final days = cubit.tripDays;
        final perDay = cubit.placesPerDay;
        final total = cubit.totalPlaces;
        final disabled = days == 0;
        final multiples = [1, 2, 3, 4];

        return Container(
          width: double.infinity,
          padding: const EdgeInsets.all(12),
          decoration: ShapeDecoration(
            color: const Color(0xFFF8FAFC),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
            ),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                "select_places_message".tr(),
                style: AppTextStyles.font16Regular.copyWith(
                  color: AppColors.black,
                ),
              ),
              HeightSpace(20.h),

              Row(
                children: [
                  Expanded(
                    child: _PlacesInfoItem(
                      label: "trip_days_label".tr(),
                      icon: AppAssets.calendarSvg,
                      value: "$days ${"day_singular".tr()}",
                    ),
                  ),
                  Expanded(
                    child: _PlacesInfoItem(
                      label: "total_places_label".tr(),
                      icon: AppAssets.calendarSvg,
                      value: "$total",
                    ),
                  ),
                ],
              ),

              HeightSpace(24.h),

              Text(
                "max_places_label".tr(),
                style: AppTextStyles.font14Regular.copyWith(
                  color: AppColors.grey,
                ),
              ),
              HeightSpace(8.h),
              Wrap(
                spacing: 12.w,
                runSpacing: 12.h,
                children: multiples.map((m) {
                  final value = days * m;
                  final selected = !disabled && total == value;
                  return _CircleOption(
                    text: value.toString(),
                    selected: selected,
                    enabled: !disabled,
                    iconChecked: selected, // الصح يظهر فقط للمختار
                    onTap: () {
                      if (!disabled) {
                        context.read<PrepareTripWizardCubit>().setFunnyPerDay(
                          m.toString(),
                        );
                      }
                    },
                  );
                }).toList(),
              ),

              HeightSpace(24.h),

              Text(
                "places_per_day_label".tr(),
                style: AppTextStyles.font14Regular.copyWith(
                  color: AppColors.grey,
                ),
              ),
              HeightSpace(12.h),
              Wrap(
                spacing: 12.w,
                runSpacing: 12.h,
                alignment: WrapAlignment.center,

                children: [1, 2, 3, 4].map((d) {
                  final selected = perDay == d;
                  return _CircleOption(
                    text: d.toString(),
                    selected: selected,
                    enabled: true,
                    iconChecked: selected, // الصح يظهر فقط للمختار
                    onTap: () {
                      context.read<PrepareTripWizardCubit>().setFunnyPerDay(
                        d.toString(),
                      );
                    },
                  );
                }).toList(),
              ),

              HeightSpace(12.h),
            ],
          ),
        );
      },
    );
  }
}

class _CircleOption extends StatelessWidget {
  final String? text;
  final bool selected;
  final bool enabled;
  final bool iconChecked;
  final VoidCallback? onTap;

  const _CircleOption({
    this.text,
    this.selected = false,
    this.enabled = true,
    this.iconChecked = false,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final bg = selected ? AppColors.primary : const Color(0xFFEDECF3);
    final fg = selected ? Colors.white : AppColors.black;
    const size = 44.0;

    return GestureDetector(
      onTap: enabled ? onTap : null,
      child: Container(
        width: size,
        height: size,
        alignment: Alignment.center,
        decoration: BoxDecoration(
          color: enabled ? bg : bg.withOpacity(0.4),
          shape: BoxShape.circle,
          boxShadow: selected
              ? [
                  BoxShadow(
                    color: AppColors.primary.withOpacity(0.25),
                    blurRadius: 8,
                  ),
                ]
              : null,
        ),
        child: iconChecked
            ? Icon(
                Icons.check,
                size: 22,
                color: selected ? Colors.white : Colors.black45,
              )
            : Text(
                text ?? '',
                style: AppTextStyles.font14Bold.copyWith(color: fg),
              ),
      ),
    );
  }
}

class _PlacesInfoItem extends StatelessWidget {
  final String label;
  final String value;
  final String icon;

  const _PlacesInfoItem({
    required this.label,
    required this.value,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Text(
          label,
          style: AppTextStyles.font14Regular.copyWith(color: AppColors.grey),
        ),
        WidthSpace(8.w),
        SvgPicture.asset(icon, width: 16.w, height: 16.h),
        WidthSpace(4.w),
        Text(
          value,
          style: AppTextStyles.font14Regular.copyWith(color: AppColors.black),
        ),
      ],
    );
  }
}
