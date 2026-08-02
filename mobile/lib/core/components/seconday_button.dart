// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:hawdaj/core/components/app_usable_button.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:flutter/material.dart';

class SecondaryButton extends StatelessWidget {
  final String title;
  final VoidCallback? onTap;
  final bool active;
  final String? iconPath;

  const SecondaryButton({
    super.key,
    required this.title,
    this.onTap,
    this.active = true,
    this.iconPath,
  });

  @override
  Widget build(BuildContext context) {
    return AppUsableButton(
      onTap: onTap,
      active: active,
      textColor: AppColors.uiBlack,
      bgColor: Color(0xffF5F7F8),
      title: title,
      // border: Border.all(width: 1, color: AppColors.inactive2),
      iconPath: iconPath,
      iconColor: AppColors.uiBlack,
    );
  }
}
