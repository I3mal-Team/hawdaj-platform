import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class TripProgramHeader extends StatelessWidget {
  const TripProgramHeader({super.key, this.onTap});
  //onTap
  final void Function()? onTap;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.all(16.w),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            'trip_program'.tr(),
            style: AppTextStyles.font20Bold.copyWith(color: Colors.black),
          ),
          GestureDetector(
            onTap: onTap,
            child: Container(
              padding: EdgeInsets.all(8.w),
              decoration: BoxDecoration(
                color: AppColors.primaryLight2,
                borderRadius: BorderRadius.circular(8.r),
              ),
              child: SvgPicture.asset(
                AppAssets.mapSvg,
                width: 16.w,
                height: 16.h,
                color: AppColors.primary,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
