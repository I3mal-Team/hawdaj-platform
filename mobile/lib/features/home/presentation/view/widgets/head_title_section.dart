import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'dart:ui' as ui;

class HeadTitleSection extends StatelessWidget {
  const HeadTitleSection({
    super.key,
    required this.title,
    this.onTap,
    this.showViewAll = true,
  });

  final String title;
  final VoidCallback? onTap;
  final bool showViewAll;

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 12.h),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(title, style: AppTextStyles.font20Bold.copyWith(height: 1.2)),
          if (showViewAll)
            GestureDetector(
              onTap: onTap,
              child: Row(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,

                // textDirection: isRtl
                //     ? ui.TextDirection.ltr
                //     : ui.TextDirection.rtl,
                children: [
                  Text(
                    "see_all".tr(),
                    style: AppTextStyles.font14Regular.copyWith(
                      color: AppColors.grey,
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
            ),
        ],
      ),
    );
  }
}
