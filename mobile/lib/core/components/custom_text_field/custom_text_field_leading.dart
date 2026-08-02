import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:hawdaj/core/components/spaces.dart';

class CustomTextFieldLeading extends StatelessWidget {
  final String? leadingIconPath;
  final Widget? leading;
  final Color? svgColor;

  const CustomTextFieldLeading({
    super.key,
    this.leadingIconPath,
    this.leading,
    this.svgColor,
  });

  bool get _isSvg =>
      leadingIconPath != null && leadingIconPath!.endsWith('.svg');

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        if (leadingIconPath != null)
          Row(
            children: [
              _isSvg
                  ? SvgPicture.asset(
                      leadingIconPath!,
                      width: 20.w,
                      colorFilter: svgColor != null
                          ? ColorFilter.mode(svgColor!, BlendMode.srcIn)
                          : null,
                    )
                  : Image.asset(leadingIconPath!, width: 20.w),
              WidthSpace(8.w),
            ],
          ),
        if (leading != null) leading!,
      ],
    );
  }
}
