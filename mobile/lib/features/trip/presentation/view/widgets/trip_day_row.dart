import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class TripDayRow extends StatelessWidget {
  const TripDayRow({
    super.key,
    required this.index,
    required this.dateText,
    required this.count,
  });

  final int index;
  final String dateText;
  final int count;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          width: 28.w,
          height: 28.h,
          decoration: ShapeDecoration(
            color: const Color(0xFFD5C2E4),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(6.r),
            ),
          ),
          alignment: Alignment.center,
          child: Text(
            index.toString(),
            style: AppTextStyles.font16Bold.copyWith(color: AppColors.dark60),
          ),
        ),
        WidthSpace(8.w),
        Flexible(
          child: Text(
            dateText,
            style: AppTextStyles.font16Bold.copyWith(
              color: AppColors.obsidianBlack,
            ),
          ),
        ),
        Text(
          "( $count ${"places".tr()} )",
          style: AppTextStyles.font12Bold.copyWith(color: AppColors.dark60),
        ),
      ],
    );
  }
}
