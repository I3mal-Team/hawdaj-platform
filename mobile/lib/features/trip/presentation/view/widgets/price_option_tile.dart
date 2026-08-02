import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class PriceOptionTile extends StatelessWidget {
  final bool isSelected;
  final String title;
  final VoidCallback? onTap;
  final String? subtitle;
  final String? imagePath;

  const PriceOptionTile({
    super.key,
    required this.isSelected,
    required this.title,
    this.onTap,
    this.subtitle,
    this.imagePath,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      borderRadius: BorderRadius.circular(8),
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(12),
        decoration: ShapeDecoration(
          gradient: isSelected
              ? const LinearGradient(
                  colors: [Colors.white, Color(0xFFF5F3F9)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                )
              : null,
          color: isSelected ? null : Colors.white,
          shape: RoundedRectangleBorder(
            side: const BorderSide(width: 1, color: Color(0xFFEEF2F6)),
            borderRadius: BorderRadius.circular(8),
          ),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            if (imagePath != null) ...[
              SvgPicture.asset(
                imagePath!,
                height: 32.h,
                width: 32.w,
                fit: BoxFit.contain,
              ),
              HeightSpace(8.h),
            ],
            Row(
              children: [
                Container(
                  width: 24.w,
                  height: 24.h,
                  decoration: BoxDecoration(
                    border: Border.all(
                      color: isSelected
                          ? AppColors.primary
                          : AppColors.lightGrey,
                      width: 1,
                    ),
                    shape: BoxShape.circle,
                  ),
                  child: Padding(
                    padding: const EdgeInsets.all(2.0),
                    child: CircleAvatar(
                      backgroundColor: isSelected
                          ? AppColors.primary
                          : AppColors.white,
                    ),
                  ),
                ),
                WidthSpace(8.w),
                Expanded(
                  child: Text(
                    title,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: AppTextStyles.font14Regular.copyWith(
                      color: AppColors.black,
                    ),
                  ),
                ),
              ],
            ),
            if (subtitle != null) ...[
              HeightSpace(8.h),
              Text(
                subtitle!,
                style: AppTextStyles.font12Regular.copyWith(
                  color: AppColors.grey,
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }
}
