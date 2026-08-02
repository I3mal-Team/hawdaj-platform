// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/styles/app_colors.dart';

class CustomCheckBoxCircleFill extends StatelessWidget {
  final bool active;
  final double? borderRadius;
  const CustomCheckBoxCircleFill({
    super.key,
    this.active = false,
    this.borderRadius,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 20.w,
      height: 20.w,
      padding: EdgeInsets.all(2.r),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(borderRadius ?? 1000),
        border: Border.all(
          width: 1.5,
          color: active ? AppColors.primary : AppColors.inactive,
        ),
      ),
      child: active
          ? Container(
              width: double.infinity,
              height: double.infinity,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(
                  (borderRadius ?? 1000) - 2.r,
                ),
                color: AppColors.primary,
              ),
            )
          : null,
    );
  }
}
