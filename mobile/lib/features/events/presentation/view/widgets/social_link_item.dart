import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/events/presentation/view/widgets/events_details_items_body.dart';
import 'package:hawdaj/features/events/presentation/view/widgets/open_link.dart';

class SocialLinkItem extends StatelessWidget {
  final String label;
  final IconData? icon;
  final String url;

  const SocialLinkItem({
    super.key,
    required this.label,
    required this.icon,
    required this.url,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () => openLink(url),
      child: Container(
        margin: EdgeInsets.only(left: 8.w, bottom: 8.h),
        padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 10.h),
        decoration: BoxDecoration(
          color: const Color(0xFFF3ECF9),
          borderRadius: BorderRadius.circular(8.r),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, color: AppColors.primary, size: 16.sp),
            SizedBox(width: 8.w),
            Text(
              label,
              style: AppTextStyles.font12Medium.copyWith(
                color: AppColors.primary,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
