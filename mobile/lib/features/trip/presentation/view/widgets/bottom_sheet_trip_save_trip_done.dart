import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class BottomSheetTripSaveTripDone extends StatelessWidget {
  const BottomSheetTripSaveTripDone({
    super.key,
    required this.title,
    this.subtitle,
    this.onTap,
    this.textButton,
  });
  final String title;
  final String? subtitle;
  final Function()? onTap;
  final String? textButton;

  @override
  Widget build(BuildContext context) {
    return Builder(
      builder: (sheetCtx) {
        return SingleChildScrollView(
          padding: EdgeInsets.only(
            bottom: MediaQuery.of(sheetCtx).viewInsets.bottom,
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.center,
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Center(
                child: Image.asset(
                  AppAssets.saveTrip,
                  width: 120.w,
                  height: 120.h,
                ),
              ),
              HeightSpace(16.h),
              Text(
                title,
                style: AppTextStyles.font18Bold.copyWith(
                  color: AppColors.obsidianBlack,
                ),
              ),
              HeightSpace(16.h),
              if (subtitle != null)
                Text(
                  subtitle!,
                  style: AppTextStyles.font12SemiBold.copyWith(
                    color: AppColors.lightGrey,
                  ),
                ),
              HeightSpace(16.h),
              if (onTap != null)
                Row(
                  children: [
                    Expanded(
                      child: PrimaryButton(
                        title: textButton ?? 'view_my_trips'.tr(),
                        iconColor: AppColors.primary,
                        onTap: onTap,
                      ),
                    ),
                  ],
                ),
            ],
          ),
        );
      },
    );
  }
}
