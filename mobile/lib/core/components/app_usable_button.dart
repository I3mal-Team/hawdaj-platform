// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:hawdaj/core/components/asset_icon.dart';
import 'package:hawdaj/core/components/custom_button_wrapper.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/components/text_components/subtitle_text.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class AppUsableButton extends StatelessWidget {
  const AppUsableButton({
    super.key,
    required this.onTap,
    required this.active,
    required this.title,
    this.bgColor,
    this.textColor,
    this.border,
    this.iconPath,
    this.iconColor,
    this.child,
    this.padding,
    this.borderRadius,
    this.iconSpacing,
    this.iconSize,
    this.width,
    this.height,
    this.style,
  });

  final VoidCallback? onTap;
  final bool active;
  final String title;
  final Color? bgColor;
  final Color? textColor;
  final Border? border;
  final String? iconPath;
  final Color? iconColor;
  final Widget? child;
  final EdgeInsets? padding;
  final double? borderRadius;
  final double? iconSpacing;
  final double? iconSize;
  final double? width;
  final double? height;
  final TextStyle? style;

  @override
  Widget build(BuildContext context) {
    return CustomButtonWrapper(
      onTap: onTap,
      active: active,
      width: width,
      height: height,
      padding: padding ?? EdgeInsets.symmetric(vertical: 13.h),
      backgroundColor: bgColor,
      borderRadius: borderRadius ?? 8.r,
      border: border,
      child:
          child ??
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Flexible(
                child: SubtitleText(
                  title,
                  color: textColor,
                  weight: FontWeight.w500,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: style,
                ),
              ),
              if (iconPath != null) WidthSpace(iconSpacing ?? 8.w),
              if (iconPath != null)
                AssetIcon(
                  iconPath!,
                  width: iconSize ?? 24,
                  height: iconSize ?? 24,
                  color: iconColor,
                ),
            ],
          ),
    );
  }
}
