import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class BottomSheetTripCalender extends StatelessWidget {
  const BottomSheetTripCalender({super.key});

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
                child: Image.asset(AppAssets.wait, width: 120.w, height: 120.h),
              ),
              HeightSpace(16.h),
              Text(
                "coming_soon".tr(),
                style: AppTextStyles.font18Bold.copyWith(
                  color: AppColors.obsidianBlack,
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}
