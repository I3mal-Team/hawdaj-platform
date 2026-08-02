import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/primary_button.dart';

class WhiteButton extends StatelessWidget {
  final String title;
  final VoidCallback? onPressed;
  const WhiteButton({super.key, required this.title, this.onPressed});

  @override
  Widget build(BuildContext context) {
    return PrimaryButton(
      title: title,
      onTap: onPressed,
      backgroundColor: Colors.white,
      style: TextStyle(
        color: Colors.black,
        fontSize: 14.sp,
        fontWeight: FontWeight.w500,
      ),
    );
  }
}
