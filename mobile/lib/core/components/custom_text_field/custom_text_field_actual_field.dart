// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables, library_private_types_in_public_api

import 'package:hawdaj/core/components/custom_text_field/custom_text_field_upper_hint.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:google_fonts/google_fonts.dart';

class CustomTextFieldActualField extends StatelessWidget {
  final double containerHeight;
  final String? hint;
  final bool allowUpperHint;
  final bool enabled;
  final TextStyle? style;
  final TextEditingController? controller;
  final FocusNode focusNode;
  final TextStyle? hintStyle;
  final bool password;
  final bool passwordShown;
  final Duration animationDuration;
  final double topSpace;
  final bool hintDown;
  final GlobalKey hintKey;
  final Function(String v) onChanged;
  final int? maxLines;
  final int? minLines;
  final List<TextInputFormatter>? inputFormatters;
  final TextInputType? inputType;
  final TextInputAction? inputAction;
  final int? maxLength;
  final Function()? onTap;

  const CustomTextFieldActualField({
    super.key,
    required this.containerHeight,
    required this.allowUpperHint,
    required this.enabled,
    required this.style,
    required this.hint,
    required this.hintStyle,
    required this.controller,
    required this.focusNode,
    required this.password,
    required this.passwordShown,
    required this.animationDuration,
    required this.topSpace,
    required this.hintDown,
    required this.hintKey,
    required this.onChanged,
    required this.maxLines,
    required this.minLines,
    required this.inputFormatters,
    required this.inputType,
    required this.inputAction,
    required this.maxLength,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: containerHeight,
      child: Stack(
        clipBehavior: Clip.none,
        alignment: hint == null || !allowUpperHint
            ? Alignment.center
            : Alignment.bottomCenter,
        children: [
          TextField(
            buildCounter:
                (
                  context, {
                  required currentLength,
                  required isFocused,
                  required maxLength,
                }) {
                  return SizedBox();
                },
            onTap: () => onTap?.call(),
            maxLength: maxLength,
            keyboardType: inputType,
            textInputAction: inputAction,
            inputFormatters: inputFormatters,
            maxLines: password ? 1 : maxLines,
            minLines: password ? 1 : minLines,
            enabled: enabled,
            style: style,
            controller: controller,
            onChanged: onChanged,
            focusNode: focusNode,
            decoration: InputDecoration(
              border: InputBorder.none,
              hintText: allowUpperHint ? null : hint,
              hintStyle:
                  hintStyle ??
                  GoogleFonts.ibmPlexSansArabic(
                    color: AppColors.inactiveText1,
                    fontSize: 16.sp,
                    fontWeight: FontWeight.w500,
                    height: 1,
                  ),
            ),
            obscureText: password && !passwordShown,
          ),
          WidthSpace(5.w),
          if (allowUpperHint)
            CustomTextFieldUpperHint(
              animationDuration: animationDuration,
              topSpace: topSpace,
              hintDown: hintDown,
              hintKey: hintKey,
              hintStyle: hintStyle,
              hint: hint,
            ),
        ],
      ),
    );
  }
}
