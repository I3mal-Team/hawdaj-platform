import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class RatingWidget extends StatelessWidget {
  final num rating;

  const RatingWidget({super.key, this.rating = 0});

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == TextDirection.rtl;
    // Don't show rating if it's 0
    if (rating == 0) {
      return Row(
        children: [
          Text(
            'جديد',
            style: TextStyle(
              color: const Color(0xFF6B7280),
              fontSize: 12.sp,
              fontFamily: 'Poppins',
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      );
    }

    return Row(
      mainAxisSize: MainAxisSize.min,
      textDirection: isRtl ? TextDirection.ltr : TextDirection.rtl,
      children: [
        Image.asset(AppAssets.starPng, width: 16.w, height: 16.h),
        SizedBox(width: 2.w),
        Text(
          '$rating',
          style: TextStyle(
            color: const Color(0xFFF59E0B),
            fontSize: 13.sp,
            fontFamily: 'Poppins',
            fontWeight: FontWeight.w600,
          ),
        ),
      ],
    );
  }
}
