// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables, library_private_types_in_public_api

import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';

class CustomTextFieldTrailing extends StatelessWidget {
  final bool password;
  final bool passwordShown;
  final Widget? trailing;
  final String? trailingIconPath;
  final VoidCallback togglePasswordShown;
  const CustomTextFieldTrailing({
    super.key,
    required this.password,
    required this.passwordShown,
    this.trailing,
    this.trailingIconPath,
    required this.togglePasswordShown,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        if (password) WidthSpace(5.w),
        if (password)
          GestureDetector(
            onTap: togglePasswordShown,
            child: SvgPicture.asset(
              passwordShown ? AppAssets.eyeOpen : AppAssets.eyeClosed,
              width: 20.w,
              height: 20.w,
              colorFilter: ColorFilter.mode(
                AppColors.inactiveText1,
                BlendMode.srcIn,
              ),
            ),
          ),
        if (trailing != null)
          Column(mainAxisSize: MainAxisSize.min, children: [trailing!]),
        if (trailingIconPath != null)
          Row(
            children: [
              WidthSpace(8.w),
              Image.asset(trailingIconPath!, width: 20.w),
            ],
          ),
      ],
    );
  }
}
