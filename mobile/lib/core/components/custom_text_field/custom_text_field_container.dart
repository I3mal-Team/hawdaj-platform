// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables, library_private_types_in_public_api

import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class CustomTextFieldContainer extends StatelessWidget {
  final Widget child;
  final Duration animationDuration;
  final GlobalKey containerKey;
  final FocusNode focusNode;
  final int? maxLines;
  final int? minLines;
  final double? height;
  final EdgeInsetsGeometry? padding;
  const CustomTextFieldContainer({
    super.key,
    required this.child,
    required this.animationDuration,
    required this.containerKey,
    required this.focusNode,
    this.maxLines,
    this.minLines,
    this.height,
    this.padding,
  });

  @override
  Widget build(BuildContext context) {
    int differenceMax = (maxLines ?? 1) - 1;
    int differenceMin = (minLines ?? 1) - 1;
    double lineHeight = 20;

    // Use custom height if provided, otherwise use calculated height
    double? containerHeight = height;
    BoxConstraints? constraints;

    if (containerHeight == null) {
      constraints = BoxConstraints(
        maxHeight: ((differenceMax * lineHeight + 64) * (64)).h,
        minHeight: (differenceMin * lineHeight + 64).h,
      );
    }

    // Use custom padding if provided, otherwise use default padding
    EdgeInsetsGeometry effectivePadding =
        padding ?? EdgeInsets.symmetric(horizontal: 16.w);

    return AnimatedContainer(
      duration: animationDuration,
      height: containerHeight,
      constraints: constraints,
      key: containerKey,
      padding: effectivePadding,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16.r),
        border: Border.all(
          width: 1,
          color: focusNode.hasFocus ? AppColors.primary : AppColors.inactive2,
        ),
        boxShadow: [
          BoxShadow(
            color: focusNode.hasFocus ? Color(0xffE7E1EE) : Colors.white,
            blurRadius: 0,
            offset: Offset(0, 0),
            spreadRadius: 4,
          ),
        ],
      ),
      child: child,
    );
  }
}
