import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/utils/app_assets.dart' show AppAssets;
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'dart:ui' as ui;

class ViewAllWidget extends StatelessWidget {
  final VoidCallback? onTap;
  const ViewAllWidget({super.key, this.onTap});

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return GestureDetector(
      onTap: onTap,
      child: Row(
        //  textDirection: isRtl ? ui.TextDirection.ltr : ui.TextDirection.rtl,
        children: [
          Text(
            "see_all".tr(),
            textAlign: TextAlign.right,
            style: TextStyle(
              color: const Color(0xFF4B5565) /* Color-Neutrals-600 */,
              fontSize: 12.sp,
              fontFamily: AppFonts.brandoArabic,
              fontWeight: FontWeight.w500,
            ),
          ),
          SizedBox(width: 4.w),
          Transform(
            alignment: Alignment.center,
            transform: Matrix4.rotationY(isRtl ? 0 : 3.1416),
            child: SvgPicture.asset(AppAssets.arrowLeft),
          ),
        ],
      ),
    );
  }
}
