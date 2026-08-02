import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class CustomWidgetsHeadTrip extends StatelessWidget {
  final String title;
  final String? buttonText;
  final VoidCallback? onButtonPressed;
  final String subtitleText;
  final bool showButton;
  final Color? backgroundColor;
  final Color? textColor;

  const CustomWidgetsHeadTrip({
    super.key,
    required this.title,
    this.buttonText,
    this.onButtonPressed,
    this.showButton = false,
    required this.subtitleText,
    this.backgroundColor,
    this.textColor,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      mainAxisAlignment: MainAxisAlignment.start,
      children: [
        Row(
          children: [
            Expanded(
              child: Text(
                title,
                style: AppTextStyles.font20Bold.copyWith(color: Colors.black),
              ),
            ),
            if (showButton)
              SizedBox(
                width: 80.w,
                child: PrimaryButton(
                  padding: const EdgeInsets.symmetric(vertical: 8),
                  title: buttonText ?? '',
                  onTap: onButtonPressed ?? () {},
                  backgroundColor: backgroundColor ?? AppColors.primaryLight,
                  textColor: textColor ?? AppColors.primary,
                ),
              ),
          ],
        ),
        HeightSpace(8.h),
        Text(
          subtitleText,
          style: AppTextStyles.font14Regular.copyWith(color: AppColors.dark60),
        ),
      ],
    );
  }
}
