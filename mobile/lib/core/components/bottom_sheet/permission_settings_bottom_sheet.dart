import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/seconday_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class PermissionSettingsBottomSheet extends StatelessWidget {
  const PermissionSettingsBottomSheet({
    super.key,
    required this.onOpenSettingsPressed,
    required this.onCancelPressed,
  });

  final VoidCallback onOpenSettingsPressed;
  final VoidCallback onCancelPressed;

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          // Settings icon
          Image.asset(AppAssets.securitySafe, width: 80.w, height: 80.h),
          HeightSpace(24.h),

          // Title
          Text(
            'فتح إعدادات الأذونات',
            style: AppTextStyles.font18Bold.copyWith(
              color: AppColors.obsidianBlack,
            ),
          ),
          HeightSpace(16.h),

          // Description
          Text(
            'يجب السماح بالوصول للكاميرا من إعدادات التطبيق لالتقاط الصور.',
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
                child: PrimaryButton(
                  title: 'فتح الإعدادات',
                  onTap: onOpenSettingsPressed,
                ),
              ),
            ],
          ),
          HeightSpace(24.h),
        ],
      ),
    );
  }
}
