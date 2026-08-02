import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';

class ProfileSubtitle extends StatelessWidget {
  final String title;

  const ProfileSubtitle({super.key, this.title = 'الاعدادات'});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Text(
          title,
          style: TextStyle(
            color: const Color(0xFF4B5565),
            fontSize: 16.sp,
            fontFamily: AppFonts.theYearOfTheCamel,
            fontWeight: FontWeight.w800,
          ),
        ),
      ],
    );
  }
}
