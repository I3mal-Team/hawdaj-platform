import 'package:cached_network_image/cached_network_image.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/primary_button.dart';

import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/custom_app_bar_home_details.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/howdah_guides_section_items.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/sliver_section_header.dart';
import 'package:hawdaj/features/rates/presentation/manager/rate_cubit/rate_cubit.dart';
import 'package:hawdaj/features/rates/presentation/view/share_your_rate_button.dart';
import 'package:share_plus/share_plus.dart';
import 'dart:ui' as ui;

class TourGuideDetailsItemsBody extends StatelessWidget {
  const TourGuideDetailsItemsBody({super.key, required this.guideModel});
  final GuideModel guideModel;
  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 8.h),
      child: CustomScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        slivers: [
          MultiBlocListener(
            listeners: [
              BlocListener<RateCubit, RateState>(
                listener: (context, state) {
                  if (state is RateAdded) {
                    // eventsDetailsCubit.getEventsInfo();
                  }
                },
              ),
            ],
            child: CustomAppBarHomeDetails(
              isSaved: false,
              isFavorite: false,
              showSaveButton: false,
              showLocationButton: false,

              fallbackImageUrl: guideModel.image,
              showFavoriteButton: false,

              onFavoriteTap: () {},
              onSaveTap: () {},
              galleryImages: [],
              onShareTap: () {
                final link =
                    guideModel.social.personalAccount ??
                    guideModel.social.linkedin ??
                    guideModel.social.instagram ??
                    guideModel.social.twitter ??
                    guideModel.social.youtube ??
                    guideModel.social.facebook ??
                    "";
                final title = guideModel.name;

                if (link.isNotEmpty) {
                  Share.share(link, subject: title);
                } else {
                  debugPrint('Cannot share empty text');
                  showCustomFailureToast('no_share_link'.tr());
                }
              },

              onLocationTap: () {},
            ),
          ),
          SliverToBoxAdapter(child: HeightSpace(16.h)),
          SliverToBoxAdapter(
            child: Padding(
              padding: EdgeInsets.all(12.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      ClipRRect(
                        borderRadius: BorderRadius.circular(12),
                        child: CachedNetworkImage(
                          imageUrl: guideModel.image,
                          width: 52.w,
                          height: 52.h,
                          fit: BoxFit.cover,
                          placeholder: (context, url) => SizedBox(
                            width: 52.w,
                            height: 52.h,
                            child: CircularProgressIndicator(strokeWidth: 2),
                          ),
                          errorWidget: (context, url, error) => Image.asset(
                            AppAssets.user,
                            width: 52.w,
                            height: 52.h,
                            fit: BoxFit.cover,
                          ),
                        ),
                      ),

                      SizedBox(width: 10.w),

                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                Expanded(
                                  child: Text(
                                    guideModel.name,
                                    style: AppTextStyles.font16Bold.copyWith(
                                      color: AppColors.obsidianBlack,
                                    ),
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                ),
                                SizedBox(width: 8.w),
                                //  RatingWidget(rating: rating),
                              ],
                            ),
                            SizedBox(height: 4.h),
                            SizedBox(
                              width: 196.w,
                              // height: 50.h,
                              child: Row(
                                children: [
                                  SvgPicture.asset(
                                    AppAssets.starSbg,
                                    width: 16.w,
                                    height: 16.h,
                                  ),
                                  Text(
                                    "${guideModel.experience}. " +
                                        "experience_many".tr(),
                                    maxLines: 2,
                                    overflow: TextOverflow.ellipsis,
                                    style: AppTextStyles.font14SemiBold
                                        .copyWith(
                                          color: AppColors.primary,
                                          height: 1.50,
                                        ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),

                      // PrimaryButton(
                      //   width: 100.w,
                      //   padding: EdgeInsets.symmetric(
                      //     horizontal: 8.w,
                      //     vertical: 8.h,
                      //   ),
                      //   title: 'contact_message'.tr(),
                      //   backgroundColor: Color(0xffF2EBF6),
                      //   iconPath: AppAssets.svgMessage,
                      //   iconColor: AppColors.primary,
                      //   textColor: AppColors.primary,
                      // ),
                    ],
                  ),
                  HeightSpace(16.h),
                  Text(
                    guideModel.description,
                    style: AppTextStyles.font14Regular.copyWith(
                      color: AppColors.obsidianBlack,
                      height: 1.50,
                    ),
                  ),
                ],
              ),
            ),
          ),
          SliverToBoxAdapter(child: HeightSpace(16.h)),

          // //TopRatedTourGuidesList
          // SliverToBoxAdapter(child: TopRatedTourGuidesList()),
          if (guideModel.languages.isNotEmpty)
            InfoHeader(
              title: "languages".tr(),
              image: AppAssets.global,
              subtitle: "languages_spoken".tr(),
            ),
          if (guideModel.languages.isNotEmpty)
            SliverToBoxAdapter(
              child: Padding(
                padding: EdgeInsets.symmetric(horizontal: 12.w),
                child: Wrap(
                  spacing: 8.w,
                  runSpacing: 8.h,
                  children: guideModel.languages
                      .map(
                        (language) => Container(
                          padding: EdgeInsets.symmetric(
                            horizontal: 12.w,
                            vertical: 6.h,
                          ),
                          decoration: BoxDecoration(
                            color: const Color(0xFFFCFCFD),
                            borderRadius: BorderRadius.circular(8.r),
                          ),
                          child: Text(
                            language.name,
                            style: AppTextStyles.font14Regular.copyWith(
                              color: AppColors.obsidianBlack,
                            ),
                          ),
                        ),
                      )
                      .toList(),
                ),
              ),
            ),

          InfoHeader(title: 'regions'.tr(), image: AppAssets.global),
          if (guideModel.regions.isNotEmpty)
            SliverToBoxAdapter(
              child: Padding(
                padding: EdgeInsets.symmetric(horizontal: 12.w),
                child: SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: guideModel.regions.map((language) {
                      return Padding(
                        padding: EdgeInsets.only(right: 8.w),
                        child: Stack(
                          children: [
                            Container(
                              width: MediaQuery.of(context).size.width * 0.8,
                              height: 134.h,
                              decoration: BoxDecoration(
                                image: DecorationImage(
                                  image: AssetImage(AppAssets.homeBar),
                                  fit: BoxFit.cover,
                                ),
                                borderRadius: BorderRadius.circular(12.r),
                              ),
                            ),
                            Positioned(
                              bottom: 12.h,
                              left: 12.w,
                              right: 12.w,
                              child: Container(
                                padding: const EdgeInsets.all(12),
                                clipBehavior: Clip.antiAlias,
                                decoration: ShapeDecoration(
                                  color: const Color(0xff544355),
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(12.r),
                                  ),
                                ),
                                child: Row(
                                  children: [
                                    Text(
                                      " ${"region_label".tr()} \t${language.name}",
                                      style: AppTextStyles.font14Regular
                                          .copyWith(color: AppColors.white),
                                    ),
                                    WidthSpace(8.w),
                                    SvgPicture.asset(
                                      AppAssets.arrowLeft,
                                      width: 16.w,
                                      height: 16.h,
                                      color: AppColors.white,
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ],
                        ),
                      );
                    }).toList(),
                  ),
                ),
              ),
            ),

          SliverToBoxAdapter(child: HeightSpace(16.h)),
          if (guideModel.ratings.isNotEmpty)
            InfoHeader(
              title: "reviews".tr(),
              image: AppAssets.svgMessage,
              subtitle: "reviews_subtitle".tr(),

              child: GestureDetector(
                onTap: () => push(
                  RoutesKeys.kSeeAllRatingsList,
                  context,
                  extra: SeeAllRatingsArgs(
                    ratings: guideModel.ratings,
                    type: guideModel.type,
                    id: guideModel.id.toString(),
                  ),
                ),
                child: Row(
                  children: [
                    Text(
                      "see_all".tr(),
                      style: AppTextStyles.font14Regular.copyWith(
                        color: AppColors.grey,
                      ),
                    ),
                    HeightSpace(4.h),
                    Transform(
                      alignment: Alignment.center,
                      transform: Matrix4.rotationY(isRtl ? 0 : 3.1416),
                      child: SvgPicture.asset(AppAssets.arrowLeft),
                    ),
                  ],
                ),
              ),
            ),
          if (guideModel.ratings.isNotEmpty)
            SliverToBoxAdapter(
              child: SizedBox(
                height: 110.h,
                child: ListView.separated(
                  scrollDirection: Axis.horizontal,
                  //  padding: const EdgeInsets.symmetric(horizontal: 16),
                  itemCount: guideModel.ratings.length,
                  separatorBuilder: (_, __) => SizedBox(width: 12.w),
                  itemBuilder: (context, index) {
                    final rate = guideModel.ratings[index];
                    return SizedBox(
                      width: 283.w,
                      child: HowdahGuidesSectionItems(
                        title: rate.name,
                        description: rate.rateText,
                        imageUrl: AppAssets.user,
                        rating: rate.rate.toString(),
                        type: guideModel.type,
                        parentId: guideModel.id.toString(),
                        onTap: () {},
                      ),
                    );
                  },
                ),
              ),
            ),

          SliverToBoxAdapter(child: HeightSpace(16.h)),
          SliverToBoxAdapter(
            child: ShareYourRateButton(
              type: guideModel.type,
              parentId: guideModel.id.toString(),
            ),
          ),
          SliverToBoxAdapter(child: HeightSpace(16.h)),
        ],
      ),
    );
  }
}
