import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';

import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/should_execute.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_cubit.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_state.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/rating_widget.dart';
import 'package:shimmer/shimmer.dart';

class CategoryDailySuggestionsItems extends StatelessWidget {
  const CategoryDailySuggestionsItems({
    super.key,
    required this.imagePath,
    required this.label,
    this.showDecoration = true,
    this.description,
    this.rating,
    required this.favoriteId,
    required this.favoriteType,
    required this.isFavorite,
  });
  final String imagePath;
  final String label;
  final String? description;
  final bool showDecoration;
  final num? rating;
  final int favoriteId;
  final String favoriteType;
  final bool isFavorite;

  @override
  Widget build(BuildContext context) {
    final favoriteCubit = context.watch<FavoriteCubit>();
    return ClipRRect(
      borderRadius: BorderRadius.circular(20),
      child: Stack(
        children: [
          CachedNetworkImage(
            imageUrl: imagePath,
            width: 276.w,
            height: 185.h,
            fit: BoxFit.cover,
            placeholder: (context, url) => Shimmer.fromColors(
              baseColor: Colors.grey.shade300,
              highlightColor: Colors.grey.shade100,
              child: Container(
                width: 276.w,
                height: 185.h,
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(8.r),
                ),
              ),
            ),
            errorWidget: (context, url, error) => Image.asset(
              AppAssets.test2,
              width: 276.w,
              height: 185.h,
              fit: BoxFit.cover,
            ),
          ),
          Positioned(
            bottom: 12,
            left: 12,
            right: 12,
            child: Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
              decoration: showDecoration
                  ? BoxDecoration(
                      borderRadius: BorderRadius.circular(12),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.brown.shade100.withOpacity(0.4),
                          offset: const Offset(0, 4),
                          blurRadius: 10,
                        ),
                      ],
                      gradient: LinearGradient(
                        colors: [
                          const Color(0xFF996633).withOpacity(0.6),
                          const Color(0xFF996633).withOpacity(0.6),
                        ],
                        begin: Alignment.bottomRight,
                        end: Alignment.topLeft,
                      ),
                    )
                  : null,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Flexible(
                        child: Text(
                          label,
                          textAlign: TextAlign.right,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: AppTextStyles.font12Regular.copyWith(
                            color: Colors.white,
                            height: 1.50,
                          ),
                        ),
                      ),
                      rating == null
                          ? const Spacer()
                          : RatingWidget(rating: (rating ?? 0).toString()),
                    ],
                  ),
                  HeightSpace(4.h),
                  SizedBox(
                    width: 228.w,
                    child: Text(
                      description ?? '',
                      //     textAlign:  TextAlign.right,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: AppTextStyles.font10Regular.copyWith(
                        color: Colors.white,
                        height: 1.40,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),

          Positioned(
            top: 12,

            left: 6,
            child: BlocListener<FavoriteCubit, FavoriteState>(
              listener: (context, state) {
                if (state is FavoriteSuccess) {
                  //  placeDetailsCubit.getPlaceInfo();
                  showCustomSuccessToast(state.message);
                } else if (state is FavoriteError) {
                  showCustomFailureToast(state.error);
                }
              },
              child: GestureDetector(
                onTap: () {
                  shouldExecute(
                    context: context,
                    callback: () {
                      favoriteCubit.addToFavorite(
                        favoriteId: favoriteId,
                        favoriteType: favoriteType,
                      );
                    },
                  );
                },
                child: SvgPicture.asset(
                  isFavorite ? AppAssets.activeHart : AppAssets.hartIconNew,
                  width: 24.w,
                  height: 24.h,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
