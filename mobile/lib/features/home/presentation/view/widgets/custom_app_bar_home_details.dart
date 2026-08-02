import 'package:cached_network_image/cached_network_image.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_top_row_details.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_top_row_details_horizontal_icon.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/gallery_image_carousel.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_calender.dart';

class CustomAppBarHomeDetails extends StatelessWidget {
  final List<String> galleryImages;
  final String? fallbackImageUrl;
  final String? tagLabel;
  final VoidCallback? onFavoriteTap;
  final VoidCallback? onBackTap;
  final VoidCallback? onSaveTap;
  final bool isSaved;
  final VoidCallback? onShareTap;
  final VoidCallback? onLocationTap;
  final bool isFavorite;
  final bool showSaveButton;
  final bool showLocationButton;
  final bool showFavoriteButton;

  const CustomAppBarHomeDetails({
    super.key,
    this.galleryImages = const [],
    required this.isFavorite,
    this.fallbackImageUrl,
    this.tagLabel,
    this.onFavoriteTap,
    this.onBackTap,
    this.onSaveTap,
    this.onShareTap,

    this.onLocationTap,
    this.showSaveButton = true,
    this.showLocationButton = true,
    this.showFavoriteButton = true,
    required this.isSaved,
  });

  @override
  Widget build(BuildContext context) {
    final topPadding = MediaQuery.of(context).padding.top;

    Widget _buildBackground() {
      if (galleryImages.isNotEmpty) {
        return GalleryImageCarousel(
          images: galleryImages,
          height: double.infinity,
          borderRadius: 8.r,
        );
      }

      if (fallbackImageUrl != null && fallbackImageUrl!.trim().isNotEmpty) {
        return CachedNetworkImage(
          imageUrl: fallbackImageUrl!,
          fit: BoxFit.cover,
          width: double.infinity,
          height: double.infinity,
          placeholder: (context, url) => Container(
            color: Colors.black12,
            alignment: Alignment.center,
            child: SizedBox(
              width: 24.r,
              height: 24.r,
              child: const CircularProgressIndicator(strokeWidth: 2),
            ),
          ),
          errorWidget: (context, url, error) => GestureDetector(
            onTap: () {
              print('Fallback image ${fallbackImageUrl}');
            },
            child: Image.asset(
              AppAssets.test2,
              fit: BoxFit.cover,
              width: double.infinity,
              height: double.infinity,
            ),
          ),
        );
      }

      return GestureDetector(
        onTap: () {
          print('Fallback image ${fallbackImageUrl}'); // Placeholder action
        },
        child: Image.asset(
          AppAssets.test2,
          fit: BoxFit.cover,
          width: double.infinity,
          height: double.infinity,
        ),
      );
    }

    return SliverAppBar(
      expandedHeight: 339.h,
      pinned: true,
      backgroundColor: Colors.transparent,
      automaticallyImplyLeading: false,
      flexibleSpace: FlexibleSpaceBar(
        background: Stack(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.all(Radius.circular(8.r)),
              child: _buildBackground(),
            ),

            // أزرار الرجوع والمفضلة
            Padding(
              padding: EdgeInsets.only(
                top: topPadding + 36.h,
                left: 16.w,
                right: 16.w,
              ),
              child: AppBarTopRowDetails(
                onBackTap: onBackTap,
                onFavoriteTap: onFavoriteTap,
                isFavorite: isFavorite,
                showFavoriteButton: showFavoriteButton,
              ),
            ),

            // أيقونات المشاركة/الحفظ/الموقع
            Positioned(
              bottom: topPadding + 12.h,
              left: 8,
              child: AppBarTopRowDetailsHorizontalIcon(
                showSave: showSaveButton,
                showLocation: showLocationButton,
                isSaved: isSaved,
                onSaveTap: showSaveButton ? onSaveTap : null,
                onShareTap: onShareTap,
                onLocationTap: onLocationTap,
              ),
            ),

            // الليبل على اليمين
            Positioned(
              bottom: topPadding + 12.h,
              right: 8,
              child: GestureDetector(
                onTap: () {
                  baseBottomSheet(
                    title: "howdaj_bot".tr(),
                    child: BottomSheetTripCalender(),
                    context: context,
                    hideNavBar: false,
                  );
                },
                child: Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 9,
                    vertical: 3,
                  ),
                  decoration: ShapeDecoration(
                    color: const Color(0x59F2EBF6),
                    shape: RoundedRectangleBorder(
                      side: BorderSide(
                        width: 1.5,
                        color: Colors.white.withOpacity(0.35),
                      ),
                      borderRadius: BorderRadius.circular(10),
                    ),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Image.asset(AppAssets.bootHodge),
                      SizedBox(width: 4.w),
                      Text(
                        tagLabel ?? 'howdaj_bot'.tr(),
                        style: AppTextStyles.font16Regular.copyWith(
                          color: AppColors.obsidianBlack,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
