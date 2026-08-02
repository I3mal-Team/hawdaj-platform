import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';

class ProfileScoresItem extends StatelessWidget {
  final String iconPath;
  final String points;
  final String title;
  final VoidCallback? onTap;

  const ProfileScoresItem({
    super.key,
    this.iconPath = AppAssets.route1,
    this.points = '٣٠٠ نقطة',
    required this.title,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Container(
          padding: EdgeInsets.symmetric(vertical: 4.w),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(8.r),
            border: Border.all(color: Colors.white, width: 5.r),
          ),
          child: Column(
            children: [
              Image.asset(
                iconPath,
                width: 24.w,
                height: 24.h,
                fit: BoxFit.fill,
              ),
              HeightSpace(8.h),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  Text(
                    points,
                    textAlign: TextAlign.right,
                    style: TextStyle(
                      color: const Color(0xFF6A4690),
                      fontSize: 12.sp,
                      fontFamily: AppFonts.brandoArabic,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                  WidthSpace(4.w),
                  Text(
                    title,
                    textAlign: TextAlign.right,
                    style: TextStyle(
                      color: const Color(0xFF6A4690),
                      fontSize: 12.sp,
                      fontFamily: AppFonts.brandoArabic,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
