// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/custom_check_box_circle.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/components/text_components/subtitle_text.dart';
import 'package:hawdaj/core/styles/app_colors.dart';

class CustomCheckBox extends StatelessWidget {
  final String? title;
  final bool active;
  final VoidCallback? onTap;
  const CustomCheckBox({
    super.key,
    this.title,
    this.active = false,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: double.infinity,
        padding: EdgeInsets.symmetric(vertical: 20.h, horizontal: 8.w),
        decoration: BoxDecoration(
          color: active ? AppColors.primaryLight : null,
          border: Border.all(
            width: 1.w,
            color: active ? AppColors.primary : AppColors.inactive,
          ),
          borderRadius: BorderRadius.circular(20.r),
        ),
        child: Row(
          children: [
            CustomCheckBoxCircleFill(active: active),
            if (title != null) WidthSpace(4.w),
            if (title != null)
              SubtitleText(
                title!,
                color: active ? AppColors.primary : AppColors.inactiveText1,
                size: 16,
                weight: FontWeight.w500,
              ),
          ],
        ),
      ),
    );
  }
}
