import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/components/spaces.dart';

class SectionField extends StatelessWidget {
  final String label;
  final Widget child;

  const SectionField({super.key, required this.label, required this.child});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(bottom: 16.h),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: AppTextStyles.font14Medium),
          HeightSpace(8.h),
          child,
        ],
      ),
    );
  }
}
