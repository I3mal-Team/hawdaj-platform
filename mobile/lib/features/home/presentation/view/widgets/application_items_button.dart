import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class ApplicationItemsButton extends StatelessWidget {
  const ApplicationItemsButton({
    super.key,
    required this.text,
    this.onTap,
    required this.image,
  });
  final String text;
  final Function()? onTap;
  final String image;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
        decoration: ShapeDecoration(
          shape: RoundedRectangleBorder(
            side: BorderSide(width: 1.w, color: AppColors.lightRed3),
            borderRadius: BorderRadius.circular(6),
          ),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            SvgPicture.asset(image),
            WidthSpace(4.w),
            Text(
              text,
              style: AppTextStyles.font12Regular.copyWith(
                color: AppColors.lightRed3 /* Color-Brand-Main */,
                fontSize: 12,
                fontFamily: 'OmnesArabic',
                fontWeight: FontWeight.w500,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
