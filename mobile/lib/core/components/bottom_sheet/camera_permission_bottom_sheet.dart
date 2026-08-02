import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/seconday_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class CameraPermissionBottomSheet extends StatelessWidget {
  const CameraPermissionBottomSheet({
    super.key,
    required this.onAllowPressed,
    required this.onCancelPressed,
  });

  final VoidCallback onAllowPressed;
  final VoidCallback onCancelPressed;

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          // Camera icon
          Image.asset(AppAssets.securitySafe, width: 80.w, height: 80.h),
          HeightSpace(24.h),

          // Title
          Text(
            'الوصول للكاميرا',
            style: AppTextStyles.font18Bold.copyWith(
              color: AppColors.obsidianBlack,
            ),
          ),
          HeightSpace(16.h),

          // Description
          Text(
            'يحتاج التطبيق للوصول إلى الكاميرا لالتقاط صورة للملف الشخصي.',
            textAlign: TextAlign.center,
            style: AppTextStyles.font14Regular.copyWith(
              color: AppColors.dark60,
            ),
          ),
          HeightSpace(24.h),

          // Buttons
          Row(
            children: [
              Expanded(
                child: SecondaryButton(title: 'إلغاء', onTap: onCancelPressed),
              ),
              WidthSpace(12.w),
              Expanded(
                child: PrimaryButton(title: 'السماح', onTap: onAllowPressed),
              ),
            ],
          ),
          HeightSpace(24.h),
        ],
      ),
    );
  }
}
