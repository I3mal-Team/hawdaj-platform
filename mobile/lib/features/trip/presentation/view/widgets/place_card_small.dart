import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/home/data/model/places_model/place_model.dart';

class PlaceCardSmall extends StatelessWidget {
  const PlaceCardSmall({super.key, required this.place});
  final PlaceModel place;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 220.w,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12.r),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 8,
            offset: const Offset(0, 3),
          ),
        ],
      ),
      clipBehavior: Clip.antiAlias,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // صورة
          // AspectRatio(
          //   aspectRatio: 16 / 9,
          //   child: place.image.isNotEmpty
          //       ? Image.network(
          //           place.image.startsWith('http')
          //               ? place.image
          //               : 'https://your.cdn/${place.image}', // عدّل الـ baseUrl حسبك
          //           fit: BoxFit.cover,
          //           errorBuilder: (_, __, ___) => Container(
          //             color: Colors.grey.shade200,
          //             alignment: Alignment.center,
          //             child: const Icon(Icons.image_not_supported_outlined),
          //           ),
          //         )
          //       : Container(
          //           color: Colors.grey.shade200,
          //           alignment: Alignment.center,
          //           child: const Icon(Icons.image_outlined),
          //         ),
          // ),
          Padding(
            padding: EdgeInsets.all(10.w),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  place.title.isNotEmpty
                      ? place.title
                      : (place.slug.isNotEmpty ? place.slug : '#${place.id}'),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: AppTextStyles.font14SemiBold.copyWith(
                    color: AppColors.obsidianBlack,
                  ),
                ),
                HeightSpace(4.h),
                Row(
                  children: [
                    Icon(
                      Icons.location_on_outlined,
                      size: 14.sp,
                      color: AppColors.dark60,
                    ),
                    WidthSpace(4.w),
                    Expanded(
                      child: Text(
                        place.address ?? '',
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: AppTextStyles.font12Regular.copyWith(
                          color: AppColors.dark60,
                        ),
                      ),
                    ),
                  ],
                ),
                HeightSpace(6.h),
                Row(
                  children: [
                    Icon(Icons.star_rounded, size: 16.sp, color: Colors.amber),
                    WidthSpace(4.w),
                    Text(
                      (place.rate ?? 0).toString(),
                      style: AppTextStyles.font12Bold.copyWith(
                        color: AppColors.obsidianBlack,
                      ),
                    ),
                    WidthSpace(6.w),
                    Text(
                      '(${place.review ?? 0})',
                      style: AppTextStyles.font12Regular.copyWith(
                        color: AppColors.dark60,
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
