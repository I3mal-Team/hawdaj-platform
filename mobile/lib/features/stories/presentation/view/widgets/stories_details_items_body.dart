import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/should_execute.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_cubit.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_state.dart';
import 'package:hawdaj/features/home/data/model/safwests_model/safwest_model.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/custom_app_bar_home_details.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/details_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/sliver_section_header.dart';
import 'package:hawdaj/features/stories/presentation/manager/stories_details/stories_details_cubit.dart';
import 'dart:ui' as ui;

import 'package:share_plus/share_plus.dart';
import 'package:url_launcher/url_launcher.dart';

class StoriesDetailsItemsBody extends StatelessWidget {
  const StoriesDetailsItemsBody({super.key, required this.stories});
  final SafwestModel stories;
  @override
  Widget build(BuildContext context) {
    final favoriteCubit = context.read<FavoriteCubit>();
    final StoriesDetailsCubit storiesDetailsCubit = context
        .read<StoriesDetailsCubit>();
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 8.h),
      child: CustomScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        slivers: [
          BlocListener<FavoriteCubit, FavoriteState>(
            listener: (context, state) {
              if (state is FavoriteSuccess) {
                //getStoriesInfo
                storiesDetailsCubit.getStoriesInfo();
                showCustomSuccessToast(state.message);
              } else if (state is FavoriteError) {
                showCustomFailureToast(state.error);
              }
            },
            child: CustomAppBarHomeDetails(
              isSaved: stories.isSaved,
              isFavorite: stories.isFavorite,
              galleryImages: stories.galleries.map((e) => e.file).toList(),
              fallbackImageUrl: stories.image,
              showLocationButton: (stories.address != null) ? true : false,
              showSaveButton: false,
              onShareTap: () {
                Share.share('http://hawdaj.net/en/stories/${stories.slug}');
              },
              onFavoriteTap: () {
                shouldExecute(
                  context: context,
                  callback: () {
                    favoriteCubit.addToFavorite(
                      favoriteId: stories.id,
                      favoriteType: stories.type,
                    );
                  },
                );
              },

              onLocationTap: () {
                final link = stories.address;

                if (link != null) {
                  launchUrl(Uri.parse(link));
                } else {
                  showCustomFailureToast("no_share_link".tr());
                }
              },
            ),
          ),

          SliverToBoxAdapter(child: HeightSpace(16.h)),
          DetailsSection(
            title: stories.title,
            rating: stories.rate.toString(),
            address: stories.location,
            description: stories.content,
          ),
          SliverToBoxAdapter(child: HeightSpace(16.h)),

          InfoHeader(
            title: "importance_title".tr(),
            subtitle: "importance_subtitle".tr(),
            image: AppAssets.medalStar,
          ),

          SliverToBoxAdapter(
            child: Text(
              stories.storyImportance,
              style: AppTextStyles.font14Regular.copyWith(
                color: AppColors.grey,
              ),
            ),
          ),
          SliverToBoxAdapter(child: HeightSpace(16.h)),

          InfoHeader(
            title: "relevance_title".tr(),
            subtitle: "relevance_subtitle".tr(),
            image: AppAssets.documentSketch,
          ),

          SliverToBoxAdapter(
            child: Text(
              stories.relevanceToPresent,
              style: AppTextStyles.font14Regular.copyWith(
                color: AppColors.grey,
              ),
            ),
          ),
          SliverToBoxAdapter(child: HeightSpace(16.h)),
          InfoHeader(
            title: "timeperiod_title".tr(),
            subtitle: "timeperiod_subtitle".tr(),
            image: AppAssets.clock,
          ),

          SliverToBoxAdapter(
            child: Text(
              stories.timePeriod,
              style: AppTextStyles.font14Regular.copyWith(
                color: AppColors.grey,
              ),
            ),
          ),
          SliverToBoxAdapter(child: HeightSpace(16.h)),
          InfoHeader(
            title: "main_characters_title".tr(),
            subtitle: "main_characters_subtitle".tr(),
            image: AppAssets.profileSvg,
          ),

          SliverToBoxAdapter(
            child: Padding(
              padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
              child: Directionality(
                textDirection: isRtl
                    ? ui.TextDirection.rtl
                    : ui.TextDirection.ltr,
                child: Text(
                  stories.mainCharacters.isEmpty
                      ? "no_characters".tr()
                      : ' ${stories.mainCharacters.join('\n• ')}',
                  style: AppTextStyles.font14Regular.copyWith(
                    color: AppColors.grey,
                  ),
                ),
              ),
            ),
          ),

          SliverToBoxAdapter(child: HeightSpace(16.h)),
        ],
      ),
    );
  }
}
