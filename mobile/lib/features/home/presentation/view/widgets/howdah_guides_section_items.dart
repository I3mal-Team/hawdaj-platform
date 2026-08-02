import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/rating_widget.dart';
import 'package:hawdaj/features/rates/presentation/view/share_your_rate_button.dart';
import 'dart:ui' as ui;

class HowdahGuidesSectionItems extends StatelessWidget {
  const HowdahGuidesSectionItems({
    super.key,
    required this.title,
    required this.description,
    required this.imageUrl,
    required this.rating,
    required this.onTap,
    this.showBorder = false,
    this.colorTextDescription,
    required this.parentId,
    required this.type,
  });

  final String title;
  final String description;
  final String imageUrl;
  final String rating;
  final VoidCallback onTap;
  final bool showBorder;
  final Color? colorTextDescription;
  final String parentId;
  final String type;

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return GestureDetector(
      onTap: () {
        baseBottomSheet(
          context: context,
          title: title,
          child: Column(
            children: [
              Row(
                children: [
                  ClipRRect(
                    borderRadius: BorderRadius.circular(12),
                    child: CachedNetworkImage(
                      imageUrl: imageUrl,
                      width: 72.w,
                      height: 72.h,
                      fit: BoxFit.cover,
                      placeholder: (context, url) => SizedBox(
                        width: 72.w,
                        height: 72.h,
                        child: CircularProgressIndicator(strokeWidth: 2),
                      ),
                      errorWidget: (context, url, error) => Image.asset(
                        AppAssets.user,
                        width: 72.w,
                        height: 72.h,
                        fit: BoxFit.cover,
                      ),
                    ),
                  ),
                  SizedBox(width: 16.w),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: [
                      Text(
                        title,
                        style: AppTextStyles.font16Bold.copyWith(
                          color: AppColors.obsidianBlack,
                        ),
                        overflow: TextOverflow.ellipsis,
                      ),
                      SizedBox(height: 8.h),
                      RatingWidget(rating: rating),
                    ],
                  ),
                ],
              ),

              HeightSpace(16.h),
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  SizedBox(height: 4.h),
                  Text(
                    description,
                    maxLines: 8,
                    overflow: TextOverflow.ellipsis,
                    style: AppTextStyles.font14SemiBold.copyWith(
                      color: AppColors.inactiveText1,
                      height: 1.50,
                    ),
                  ),
                ],
              ),

              Padding(
                padding: const EdgeInsets.all(8.0),
                child: ShareYourRateButton(
                  type: type,
                  parentId: parentId,
                  width: MediaQuery.of(context).size.width,
                ),
              ),
            ],
          ),
          hideNavBar: true,
        );
      },
      child: Card(
        color: Colors.white,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(8.r),
          side: showBorder
              ? BorderSide(width: 1, color: Colors.grey[200]!)
              : BorderSide.none,
        ),
        elevation: 0,
        margin: EdgeInsets.zero,
        child: Padding(
          padding: EdgeInsets.all(12.w),
          child: Column(
            children: [
              Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  ClipRRect(
                    borderRadius: BorderRadius.circular(12),
                    child: CachedNetworkImage(
                      imageUrl: imageUrl,
                      width: 66.w,
                      height: 66.h,
                      fit: BoxFit.cover,
                      placeholder: (context, url) => SizedBox(
                        width: 66.w,
                        height: 66.h,
                        child: CircularProgressIndicator(strokeWidth: 2),
                      ),
                      errorWidget: (context, url, error) => Image.asset(
                        AppAssets.user,
                        width: 66.w,
                        height: 66.h,
                        fit: BoxFit.cover,
                      ),
                    ),
                  ),

                  SizedBox(width: 10.w),

                  // النصوص والتقييم
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Expanded(
                              child: Text(
                                title,
                                style: AppTextStyles.font16Bold.copyWith(
                                  color: AppColors.obsidianBlack,
                                ),
                                overflow: TextOverflow.ellipsis,
                              ),
                            ),
                            SizedBox(width: 8.w),
                            RatingWidget(rating: rating),
                          ],
                        ),
                        SizedBox(height: 4.h),
                        SizedBox(
                          width: 196.w,
                          height: 50.h,
                          child: Text(
                            description,
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                            style: AppTextStyles.font14SemiBold.copyWith(
                              color:
                                  colorTextDescription ??
                                  AppColors.inactiveText1,
                              height: 1.50,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
