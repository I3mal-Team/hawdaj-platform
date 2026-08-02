import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/rating_widget.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'dart:ui' as ui;

class TasneefTourGuidesItemCard extends StatelessWidget {
  final UnifiedPlaceModel guide;

  const TasneefTourGuidesItemCard({super.key, required this.guide});

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    // Helper method to get display name with nickname
    String getDisplayName() {
      if (guide.nickName != null && guide.nickName!.isNotEmpty) {
        return '${guide.title} (${guide.nickName})';
      }
      return guide.title.isNotEmpty ? guide.title : 'مرشد سياحي';
    }

    // Helper method to format experience text
    String getExperienceText() {
      if (guide.experience != null) {
        return '${guide.experience} ${guide.experience == 1 ? 'year_singular'.tr() : 'year_plural'.tr()} ${"experience".tr()}';
      }
      return '';
    }

    return Container(
      clipBehavior: Clip.hardEdge,
      padding: EdgeInsets.all(12.r),
      margin: EdgeInsets.only(bottom: 12.h),
      width: double.infinity,
      height: 135.h, // Increased height to accommodate more info
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(8.r),
        border: Border.all(width: 1, color: const Color(0xffF8FAFC)),
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 4,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        children: [
          // Guide Image
          Container(
            clipBehavior: Clip.hardEdge,
            width: 75.w,
            height: 75.h,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(12.r),
              border: Border.all(color: const Color(0xFFE5E7EB), width: 1),
            ),
            child: guide.fullImageUrl.isNotEmpty
                ? Image.network(
                    guide.fullImageUrl,
                    fit: BoxFit.cover,
                    width: double.infinity,
                    loadingBuilder: (context, child, loadingProgress) {
                      if (loadingProgress == null) return child;
                      return Container(
                        color: Colors.grey[100],
                        child: Center(
                          child: SizedBox(
                            width: 20.w,
                            height: 20.h,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              valueColor: AlwaysStoppedAnimation<Color>(
                                Theme.of(context).primaryColor,
                              ),
                            ),
                          ),
                        ),
                      );
                    },
                    errorBuilder: (context, error, stackTrace) {
                      return Container(
                        color: Colors.grey[100],
                        child: Icon(
                          Icons.person,
                          size: 30.sp,
                          color: Colors.grey[400],
                        ),
                      );
                    },
                  )
                : Container(
                    color: Colors.grey[100],
                    child: Icon(
                      Icons.person,
                      size: 30.sp,
                      color: Colors.grey[400],
                    ),
                  ),
          ),

          WidthSpace(12.w),
          // Guide Information
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Name and Rating Row
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Text(
                        getDisplayName(),
                        textAlign: isRtl ? TextAlign.right : TextAlign.left,
                        style: TextStyle(
                          color: Colors.black,
                          fontSize: 16.sp,
                          fontFamily: AppFonts.theYearOfTheCamel,
                          fontWeight: FontWeight.w800,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    WidthSpace(8.w),
                    RatingWidget(rating: guide.rate),
                  ],
                ),
                HeightSpace(4.h),
                // Description
                if (guide.description.isNotEmpty) ...[
                  Text(
                    guide.description,
                    textAlign: TextAlign.right,
                    style: TextStyle(
                      color: const Color(0xFF6B7280),
                      fontSize: 13.sp,
                      fontFamily: AppFonts.brandoArabic,
                      fontWeight: FontWeight.w400,
                      height: 1.3,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  HeightSpace(6.h),
                ],
                // Experience and Regions Row
                Row(
                  children: [
                    // Experience
                    if (guide.experience != null) ...[
                      Icon(
                        Icons.star_rounded,
                        size: 14.sp,
                        color: const Color(0xFFF59E0B),
                      ),
                      WidthSpace(4.w),
                      Text(
                        getExperienceText(),
                        style: TextStyle(
                          color: const Color(0xFF374151),
                          fontSize: 12.sp,
                          fontFamily: AppFonts.brandoArabic,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ],
                    // Separator
                    if (guide.experience != null &&
                        guide.regionsText.isNotEmpty) ...[
                      WidthSpace(8.w),
                      Container(
                        width: 4.w,
                        height: 4.h,
                        decoration: const BoxDecoration(
                          color: Color(0xFFD1D5DB),
                          shape: BoxShape.circle,
                        ),
                      ),
                      WidthSpace(8.w),
                    ],
                    // Regions
                    if (guide.regionsText.isNotEmpty) ...[
                      Icon(
                        Icons.location_on_rounded,
                        size: 14.sp,
                        color: const Color(0xFF6B7280),
                      ),
                      WidthSpace(4.w),
                      Expanded(
                        child: Text(
                          guide.regionsText,
                          style: TextStyle(
                            color: const Color(0xFF6B7280),
                            fontSize: 12.sp,
                            fontFamily: AppFonts.brandoArabic,
                            fontWeight: FontWeight.w400,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ],
                  ],
                ),
                HeightSpace(4.h),
                // Languages Row
                if (guide.languagesText.isNotEmpty) ...[
                  Row(
                    children: [
                      Icon(
                        Icons.translate_rounded,
                        size: 14.sp,
                        color: const Color(0xFF6B7280),
                      ),
                      WidthSpace(4.w),
                      Expanded(
                        child: Text(
                          guide.languagesText,
                          style: TextStyle(
                            color: const Color(0xFF6B7280),
                            fontSize: 12.sp,
                            fontFamily: AppFonts.brandoArabic,
                            fontWeight: FontWeight.w400,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                      // Show ratings count if available
                      if (guide.ratings.isNotEmpty) ...[
                        WidthSpace(8.w),
                        Text(
                          '(${guide.ratings.length} ${"rating_count".tr()})',
                          style: TextStyle(
                            color: const Color(0xFF9CA3AF),
                            fontSize: 11.sp,
                            fontFamily: AppFonts.brandoArabic,
                            fontWeight: FontWeight.w400,
                          ),
                        ),
                      ],
                    ],
                  ),
                ],
              ],
            ),
          ),
        ],
      ),
    );
  }
}
