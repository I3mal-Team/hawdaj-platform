import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class CustomFilterChip extends StatelessWidget {
  const CustomFilterChip({
    super.key,
    required this.isSelected,
    this.onSelected,
    required this.label,
  });

  final String label;
  final bool isSelected;
  final void Function(bool)? onSelected;

  @override
  Widget build(BuildContext context) {
    return FilterChip(
      padding: EdgeInsets.zero,
      label: Text(label),
      selected: isSelected,
      onSelected: onSelected,
      selectedColor: AppColors.primary,
      showCheckmark: false,
      backgroundColor: Colors.white,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      labelStyle: AppTextStyles.font12Medium.copyWith(
        color: isSelected ? AppColors.white : AppColors.uiBlack,
      ),
      side: BorderSide(color: AppColors.darkWhite, width: isSelected ? 0 : 1),
      labelPadding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 4.h),
      materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
    );
  }
}
