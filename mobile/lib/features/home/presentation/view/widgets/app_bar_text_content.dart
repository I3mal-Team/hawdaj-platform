import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class AppBarTextContent extends StatelessWidget {
  const AppBarTextContent({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Text(
          "welcome_howdaj".tr(),
          style: AppTextStyles.font16SemiBold.copyWith(
            color: AppColors.obsidianBlack,
          ),
          textAlign: TextAlign.center,
        ),
        SizedBox(height: 8.h),
        Text(
          'journey_deep_heritage'.tr(),
          maxLines: 2,
          overflow: TextOverflow.ellipsis,
          style: AppTextStyles.font22Bold.copyWith(
            color: AppColors.obsidianBlack,
          ),
          textAlign: TextAlign.center,
        ),
      ],
    );
  }
}
