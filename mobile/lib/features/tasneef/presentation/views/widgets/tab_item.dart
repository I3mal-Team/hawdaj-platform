import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';

class TabItem extends StatelessWidget {
  final String title;
  final bool active;
  final VoidCallback? onTap;
  final Object? data; // holds full model if needed
  const TabItem({
    super.key,
    this.active = false,
    required this.title,
    this.onTap,
    this.data,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: EdgeInsets.only(left: 6.w),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
        decoration: ShapeDecoration(
          color: active
              ? const Color(0xFFEBE1F2) /* Color-Brand-100 */
              : Colors.white,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6)),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.center,
          spacing: 10,
          children: [
            Text(
              title,
              textAlign: TextAlign.center,
              style: TextStyle(
                color: active ? Color(0xFF6A4690) : Color(0xff697586),
                fontSize: 14.sp,
                fontFamily: AppFonts.theYearOfTheCamel,
                fontWeight: FontWeight.w900,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
