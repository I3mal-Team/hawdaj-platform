import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'dart:ui' as ui;

class PointsCard extends StatelessWidget {
  final String? title;
  final String? subtitle;
  final String? buttonText;
  final int points; // عدد النقاط بدلاً من نص جاهز
  final String pointsImagePath;
  final VoidCallback? onButtonTap;

  const PointsCard({
    super.key,
    this.title,
    this.subtitle,
    this.buttonText,
    this.points = 5000,
    this.pointsImagePath = AppAssets.route1,
    this.onButtonTap,
  });

  @override
  Widget build(BuildContext context) {
    final displayTitle = title ?? "points_card_title".tr();
    final displaySubtitle = subtitle ?? "points_card_subtitle".tr();
    final displayButton = buttonText ?? "points_card_add_landmark".tr();
    final pointsUnit = "points_card_points_unit".tr();
    final pointsText = '$points $pointsUnit';
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;

    return Stack(
      clipBehavior: Clip.none,
      children: [
        Container(
          width: double.infinity,
          padding: EdgeInsets.all(12.r),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(12.r),
            gradient: const LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: [Color(0xffFFE6DD), Color(0xffD9B2CE)],
            ),
          ),
          child: Column(
            crossAxisAlignment: isRtl
                ? CrossAxisAlignment.start
                : CrossAxisAlignment.end,
            children: [
              Text(
                displayTitle,
                textAlign: TextAlign.right,
                style: TextStyle(
                  color: Colors.black,
                  fontSize: 16.sp,
                  fontFamily: AppFonts.theYearOfTheCamel,
                  fontWeight: FontWeight.w900,
                ),
              ),
              HeightSpace(8.h),
              Text(
                displaySubtitle,
                textAlign: TextAlign.right,
                style: TextStyle(
                  color: const Color(0xFF697586),
                  fontSize: 12.sp,
                  fontFamily: AppFonts.brandoArabic,
                  fontWeight: FontWeight.w500,
                ),
              ),
              HeightSpace(8.h),
              GestureDetector(
                onTap: onButtonTap,
                child: Container(
                  padding: EdgeInsets.symmetric(
                    horizontal: 16.w,
                    vertical: 8.h,
                  ),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(6.r),
                    color: const Color(0xff6A4690),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Image.asset(AppAssets.addItem, width: 16.w),
                      WidthSpace(4.w),
                      Text(
                        displayButton,
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 12.sp,
                          fontFamily: AppFonts.brandoArabic,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
        Positioned(
          left: 0,
          bottom: -15.h,
          child: Column(
            children: [
              Text(
                pointsText,
                textAlign: TextAlign.right,
                style: TextStyle(
                  color: const Color(0xFF6A4690),
                  fontSize: 14.sp,
                  fontFamily: AppFonts.brandoArabic,
                  fontWeight: FontWeight.w700,
                ),
              ),
              Image.asset(pointsImagePath, width: 96.w),
            ],
          ),
        ),
      ],
    );
  }
}
