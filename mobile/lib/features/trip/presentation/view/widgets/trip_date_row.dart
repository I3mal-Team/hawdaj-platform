import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class TripDateRow extends StatelessWidget {
  final String label; // النص قبل التاريخ
  final String subtitle; // التاريخ نفسه
  final String iconPath; // مسار الأيقونة SVG

  const TripDateRow({
    super.key,
    required this.label,
    required this.subtitle,
    this.iconPath = AppAssets.calendarSvg, // افتراضي أيقونة التقويم
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        SvgPicture.asset(iconPath),
        WidthSpace(2.w),
        Expanded(
          child: Row(
            children: [
              Text(
                label,
                style: AppTextStyles.font12Regular.copyWith(
                  color: AppColors.dark60,
                ),
              ),
              WidthSpace(2.w),
              Expanded(
                child: Text(
                  subtitle,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: AppTextStyles.font14Bold.copyWith(color: Colors.black),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}
