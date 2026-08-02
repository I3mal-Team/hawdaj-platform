// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:hawdaj/core/components/app_usable_button.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:flutter/material.dart';

class PrimaryButton extends StatelessWidget {
  final String title;
  final VoidCallback? onTap;
  final bool active;
  final String? iconPath;
  final Color? backgroundColor;
  final Color? iconColor;
  final Color? textColor;
  final Widget? child;
  final EdgeInsets? padding;
  final double? borderRadius;
  final double? iconSpacing;
  final double? iconSize;
  final double? height;
  final double? width;
  final TextStyle? style;

  const PrimaryButton({
    super.key,
    required this.title,
    this.onTap,
    this.active = true,
    this.iconPath,
    this.backgroundColor,
    this.child,
    this.textColor,
    this.padding,
    this.borderRadius,
    this.iconColor,
    this.iconSpacing,
    this.iconSize,
    this.height,
    this.width,
    this.style,
  });

  @override
  Widget build(BuildContext context) {
    return AppUsableButton(
      height: height,
      width: width,
      onTap: onTap,
      active: active,
      title: title,
      textColor: textColor ?? Colors.white,
      bgColor: backgroundColor ?? AppColors.lightRed3,
      iconPath: iconPath,
      iconColor: iconColor,
      padding: padding,
      borderRadius: borderRadius,
      iconSize: iconSize,
      iconSpacing: iconSpacing,
      style: style,
      child: child,
    );
  }
}
