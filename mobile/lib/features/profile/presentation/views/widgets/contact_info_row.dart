import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart' show WidthSpace;
import 'package:hawdaj/core/styles/app_text_styles.dart';

class ContactInfoRow extends StatelessWidget {
  final String iconPath;
  final String text;
  final Color? textColor;
  final Color? iconColor;
  final double iconSize;
  final VoidCallback? onTap;

  const ContactInfoRow({
    super.key,
    required this.iconPath,
    required this.text,
    this.textColor,
    this.iconColor,
    this.iconSize = 24,
    this.onTap,
  });

  bool get _isSvg => iconPath.toLowerCase().endsWith('.svg');

  @override
  Widget build(BuildContext context) {
    final iconWidget = _isSvg
        ? SvgPicture.asset(
            iconPath,
            width: iconSize.w,
            height: iconSize.h,
            colorFilter: iconColor != null
                ? ColorFilter.mode(iconColor!, BlendMode.srcIn)
                : null,
          )
        : Image.asset(
            iconPath,
            width: iconSize.w,
            height: iconSize.h,
            errorBuilder: (_, __, ___) => const SizedBox(), // fallback بسيط
          );

    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(8),
      child: Padding(
        padding: const EdgeInsets.all(8.0),
        child: Row(
          children: [
            iconWidget,
            WidthSpace(8.w),
            Expanded(
              child: Text(
                text,
                textDirection: TextDirection.ltr,
                textAlign: TextAlign.right,
                style: AppTextStyles.font16Bold.copyWith(
                  color: textColor ?? Colors.black,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
