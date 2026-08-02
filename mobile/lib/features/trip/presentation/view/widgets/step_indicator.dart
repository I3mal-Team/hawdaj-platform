import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class StepIndicator extends StatelessWidget {
  final List<String> steps;
  final int currentIndex;

  const StepIndicator({
    super.key,
    required this.steps,
    required this.currentIndex,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 16),
      decoration: ShapeDecoration(
        gradient: LinearGradient(
          begin: Alignment.centerLeft,
          end: Alignment.centerRight,
          colors: [const Color(0xFFFDF9F4), const Color(0xFFFBF8FE)],
        ),
        shape: RoundedRectangleBorder(
          side: BorderSide(width: 1, color: const Color(0x336A4690)),
          borderRadius: BorderRadius.circular(12),
        ),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: steps.asMap().entries.map((entry) {
          final index = entry.key;
          final title = entry.value;

          final isPastOrCurrent = index <= currentIndex;

          return Expanded(
            child: Column(
              children: [
                Text(
                  title,
                  textAlign: TextAlign.center,
                  style: AppTextStyles.font12Regular.copyWith(
                    color: isPastOrCurrent
                        ? AppColors.obsidianBlack
                        : Colors.grey,
                  ),
                ),
                SizedBox(height: 8.h),

                Container(
                  height: 3.h,
                  width: 3.w,
                  decoration: BoxDecoration(
                    color: isPastOrCurrent
                        ? AppColors.primary
                        : Colors.grey[300],
                    borderRadius: BorderRadius.circular(100.r),
                  ),
                ),

                SizedBox(height: 8.h),
                Container(
                  height: 8.h,
                  margin: EdgeInsets.symmetric(horizontal: 8.w),
                  width: double.infinity,
                  decoration: BoxDecoration(
                    color: isPastOrCurrent
                        ? AppColors.primary
                        : Colors.grey[300],
                    borderRadius: BorderRadius.circular(100.r),
                  ),
                ),
              ],
            ),
          );
        }).toList(),
      ),
    );
  }
}
