import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';

class LogoutButton extends StatelessWidget {
  final String? text;
  final VoidCallback? onTap;

  const LogoutButton({super.key, this.text, this.onTap});

  @override
  Widget build(BuildContext context) {
    final displayText = text ?? "logout".tr();
    return GestureDetector(
      onTap: onTap,
      child: Container(
        alignment: Alignment.center,
        padding: EdgeInsets.all(12.r),
        width: double.infinity,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(8.r),
          color: const Color(0xffF9E7E8),
        ),
        child: Text(
          displayText,
          style: TextStyle(
            color: const Color(0xFFCA4146),
            fontSize: 14.sp,
            fontFamily: AppFonts.brandoArabic,
            fontWeight: FontWeight.w500,
          ),
        ),
      ),
    );
  }
}
