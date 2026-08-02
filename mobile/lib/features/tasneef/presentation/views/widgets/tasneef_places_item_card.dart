import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/global_glossy_container.dart';
import 'package:hawdaj/core/components/spaces.dart';
import '../../../data/models/unified_place_model.dart';
import 'dart:ui' as ui;

class TasneefPlacesItemCard extends StatelessWidget {
  final bool manor;
  final UnifiedPlaceModel? place;
  final VoidCallback? onTapFavorite;
  final VoidCallback? goToDetails;
  final bool isFavorite;
  final Widget? child;
  final Widget? icon2;

  const TasneefPlacesItemCard({
    super.key,
    this.manor = false,
    this.place,
    this.onTapFavorite,
    this.goToDetails,
    this.isFavorite = true,
    this.child,
    this.icon2,
  });

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    final title = place?.title ?? 'حافة العالم';
    final location = place?.locationText ?? '..................';
    final num rating = place?.rate ?? 0;
    final imageUrl = place?.fullCoverImageUrl;

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
            _buildImage(imageUrl),
            _buildGradientOverlay(),
            _buildBottomContent(title, location, rating, isRtl),
            _buildTopRow(isRtl),
            if (icon2 != null) _buildIcon2(isRtl),
          ],
        ),
      ),
    );
  }

  Widget _buildImage(String? imageUrl) {
    if (imageUrl != null) {
      return Image.network(
        imageUrl,
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
      );
    }
    return Image.asset(
      AppAssets.onboarding(1),
      fit: BoxFit.cover,
      width: double.infinity,
      height: double.infinity,
    );
  }

  Widget _buildGradientOverlay() {
    return Container(
      width: double.infinity,
      height: 64.h,
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topCenter,
          end: Alignment.bottomCenter,
          colors: [Colors.black.withOpacity(0), Colors.black.withOpacity(1)],
        ),
      ),
    );
  }

  Widget _buildBottomContent(
    String title,
    String location,
    num rating,
    bool isRtl,
  ) {
    return Positioned(
      bottom: 12.h,
      left: 12.w,
      right: 12.w,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: Text(
                  title,
                  textAlign: isRtl ? TextAlign.right : TextAlign.left,
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 14.sp,
                    fontFamily: AppFonts.brandoArabic,
                    fontWeight: FontWeight.w500,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              SizedBox(width: 8.w),
              Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  SvgPicture.asset(AppAssets.star, width: 14.w),
                  SizedBox(width: 4.w),
                  Text(
                    '(${rating.toStringAsFixed(1)})',
                    style: TextStyle(
                      color: const Color(0xFFFDC800),
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
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                location,
                textAlign: isRtl ? TextAlign.right : TextAlign.left,
                style: TextStyle(
                  color: const Color(0xFFF8FAFC),
                  fontSize: 12.sp,
                  fontFamily: AppFonts.brandoArabic,
                  fontWeight: FontWeight.w400,
                ),
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
              ),
              if (manor && place?.type == 'event') ...[
                HeightSpace(4.h),
                Text(
                  "${place?.dateFrom} - ${place?.dateTo}",
                  textAlign: isRtl ? TextAlign.right : TextAlign.left,
                  style: GoogleFonts.poppins(
                    color: const Color(0xFFF8FAFC),
                    fontSize: 10.sp,
                    fontWeight: FontWeight.w400,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildTopRow(bool isRtl) {
    return Positioned(
      top: 12.h,
      left: 12.w,
      right: 12.w,
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [_buildStatusBadge(isRtl), _buildFavoriteIcon()],
      ),
    );
  }

  Widget _buildStatusBadge(bool isRtl) {
    if (manor && place?.type == 'store') {
      return GlobalGlossyContainer(
        padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 7.h),
        child: Text(
          place?.priceText ?? 'open'.tr(),
          textAlign: TextAlign.center,
          style: TextStyle(
            color: Colors.white,
            fontSize: 12.sp,
            fontFamily: AppFonts.brandoArabic,
            fontWeight: FontWeight.w500,
          ),
        ),
      );
    } else if (manor && place?.type == 'event') {
      return GlobalGlossyContainer(
        padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 7.h),
        child: Text(
          place?.closed == true ? 'almost'.tr() : 'open'.tr(),
          textAlign: TextAlign.center,
          style: TextStyle(
            color: Colors.white,
            fontSize: 12.sp,
            fontFamily: AppFonts.brandoArabic,
            fontWeight: FontWeight.w500,
          ),
        ),
      );
    }
    return const SizedBox.shrink();
  }

  Widget _buildFavoriteIcon() {
    if (isFavorite) {
      return GestureDetector(
        onTap: onTapFavorite,
        child: SvgPicture.asset(
          place?.isFavorite == true ? AppAssets.activeHart : AppAssets.hartIcon,
          width: 25.w,
          height: 25.w,
        ),
      );
    } else if (child != null) {
      return child!;
    }
    return const SizedBox.shrink();
  }

  Widget _buildIcon2(bool isRtl) {
    return PositionedDirectional(
      top: 12.h,
      start: 12.w,
      child: GlobalGlossyContainer(child: icon2!),
    );
  }
}
