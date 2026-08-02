import 'dart:ui' as ui;
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/landmarks/data/models/list_landmark_response.dart';

class LandMarkItems extends StatelessWidget {
  const LandMarkItems({super.key, this.landmarkItem});
  final LandmarkItem? landmarkItem;

  @override
  Widget build(BuildContext context) {
    final imageUrl = landmarkItem?.image;
    final title = landmarkItem?.title;
    final description = landmarkItem?.description;
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return Container(
      clipBehavior: Clip.hardEdge,
      margin: EdgeInsets.only(bottom: 12.h),
      width: double.infinity,
      height: 185.h,
      decoration: BoxDecoration(borderRadius: BorderRadius.circular(12.r)),
      child: Stack(
        alignment: Alignment.bottomCenter,
        children: [
          // Image - use network image if place data available, otherwise asset
          imageUrl != null
              ? Image.network(
                  imageUrl!,
                  fit: BoxFit.cover,
                  width: double.infinity,
                  height: double.infinity,
                  errorBuilder: (context, error, stackTrace) {
                    return Image.asset(
                      AppAssets.onboarding(1),
                      fit: BoxFit.cover,
                      width: double.infinity,
                      height: double.infinity,
                    );
                  },
                )
              : Image.asset(
                  AppAssets.onboarding(1),
                  fit: BoxFit.cover,
                  width: double.infinity,
                  height: double.infinity,
                ),
          Container(
            width: double.infinity,
            height: 64.h,
            decoration: BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topCenter,
                end: Alignment.bottomCenter,
                colors: [
                  Colors.black.withOpacity(0),
                  Colors.black.withOpacity(1),
                ],
              ),
            ),
          ),
          Padding(
            padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 12.h),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Text(
                        title ?? '',
                        textAlign: isRtl ? TextAlign.right : TextAlign.left,
                        style: TextStyle(
                          color: Colors.white /* Color-Neutrals-White */,
                          fontSize: 14.sp,
                          fontFamily: AppFonts.brandoArabic,
                          fontWeight: FontWeight.w500,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    Row(
                      textDirection: isRtl
                          ? ui.TextDirection.ltr
                          : ui.TextDirection.rtl,
                      children: [
                        SvgPicture.asset(AppAssets.star, width: 14.w),
                        Text(
                          '({rating.toStringAsFixed(1)} )',
                          style: TextStyle(
                            color: const Color(
                              0xFFFDC800,
                            ) /* Color-Accent-(-Yellow-)-300 */,
                            fontSize: 12.sp,
                            fontFamily: 'Poppins',
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
                HeightSpace(4.h),
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        "location",
                        textAlign: isRtl ? TextAlign.right : TextAlign.left,
                        style: TextStyle(
                          color: const Color(
                            0xFFF8FAFC,
                          ) /* Color-Neutrals-50 */,
                          fontSize: 12.sp,
                          fontFamily: AppFonts.brandoArabic,
                          fontWeight: FontWeight.w400,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
