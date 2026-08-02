import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';

class TasneefStoriesItemCard extends StatelessWidget {
  final UnifiedPlaceModel? story;
  final VoidCallback? goToDetails;

  const TasneefStoriesItemCard({super.key, this.story, this.goToDetails});

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == TextDirection.rtl;
    return GestureDetector(
      onTap: goToDetails,
      child: Container(
        clipBehavior: Clip.hardEdge,
        margin: EdgeInsets.only(bottom: 12.h),
        width: double.infinity,
        height: 185.h,
        decoration: BoxDecoration(borderRadius: BorderRadius.circular(12.r)),
        child: Stack(
          alignment: Alignment.bottomCenter,
          children: [
            story != null
                ? CachedNetworkImage(
                    imageUrl: story!.fullImageUrl,
                    fit: BoxFit.cover,
                    width: double.infinity,
                    height: double.infinity,
                    placeholder: (context, url) => Container(
                      color: Colors.grey[300],
                      child: const Center(child: CircularProgressIndicator()),
                    ),
                    errorWidget: (context, url, error) {
                      return Image.asset(
                        AppAssets.onboarding(1),
                        fit: BoxFit.cover,
                        width: double.infinity,
                      );
                    },
                  )
                : Image.asset(
                    AppAssets.onboarding(1),
                    fit: BoxFit.cover,
                    width: double.infinity,
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
                          story?.title ?? '',
                          textAlign: isRtl ? TextAlign.right : TextAlign.left,
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 14.sp,
                            fontFamily: AppFonts.brandoArabic,
                            fontWeight: FontWeight.w500,
                          ),
                          maxLines: 2,
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
      ),
    );
  }
}
