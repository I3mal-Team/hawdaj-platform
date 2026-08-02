import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class RegionField extends StatelessWidget {
  final String title;
  final VoidCallback? onTap;

  const RegionField({super.key, required this.title, this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: double.infinity,
        height: 56.h,
        padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
        decoration: ShapeDecoration(
          color: Colors.white, // Dark-White
          shape: RoundedRectangleBorder(
            side: const BorderSide(
              width: 1,
              color: Color(0xFFEEF2F6), // Color-Neutrals-100
            ),
            borderRadius: BorderRadius.circular(12),
          ),
        ),
        child: Row(
          children: [
            SvgPicture.asset(AppAssets.mapSvg, width: 24.w, height: 24.h),
            WidthSpace(8.w),
            Text(
              title,
              style: AppTextStyles.font14Regular.copyWith(
                color: AppColors.obsidianBlack,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
