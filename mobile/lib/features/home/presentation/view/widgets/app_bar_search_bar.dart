import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';

import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'dart:ui' as ui;

class AppBarSearchBar extends StatelessWidget {
  const AppBarSearchBar({super.key});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        push(RoutesKeys.kSearchView, context);
      },
      child: Container(
        height: 50.h,
        margin: EdgeInsets.symmetric(horizontal: 16.w),
        padding: EdgeInsets.symmetric(horizontal: 20.w),
        decoration: BoxDecoration(
          color: const Color(0xFFE7D1CF).withOpacity(0.4),
          borderRadius: BorderRadius.circular(50.r),
          border: Border(
            right: BorderSide(color: Colors.white.withOpacity(0.6), width: 1.2),
            left: BorderSide(color: Colors.white.withOpacity(0.6), width: 1.2),
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 8,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Row(
          children: [
            SvgPicture.asset(AppAssets.searchNormal),
            SizedBox(width: 10.w),
            Expanded(
              child: Text(
                'search_anything'.tr(),
                style: AppTextStyles.font12Regular.copyWith(
                  color: Color(0xFF364152),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
