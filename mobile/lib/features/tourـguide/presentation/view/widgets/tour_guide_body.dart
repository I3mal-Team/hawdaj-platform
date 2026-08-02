import 'package:cached_network_image/cached_network_image.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/howdah_guides_section_items.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/sliver_section_header.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/fetch_all_tour_guide_cubit/fetch_all_tour_guide_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/social_links_section.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'dart:ui' as ui;

class TourGuideBody extends StatelessWidget {
  const TourGuideBody({super.key, required this.guideModel});
  final GuideModel guideModel;
  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return CustomScrollView(
      physics: const AlwaysScrollableScrollPhysics(),
      slivers: [
        SliverToBoxAdapter(
          child: TripStartAppBar(
            title: "tour_guide_profile".tr(),
            onBack: () {
              pop(context, true);
            },
            extraIcon: GestureDetector(
              onTap: () async {
                final result = await push(
                  RoutesKeys.kAddTourGuideDetailsView,
                  context,
                  extra: guideModel,
                );

                // لو رجعت الصفحة بنتيجة نجاح → اعمل Refresh للبيانات
                if (result == true && context.mounted) {
                  context.read<FetchAllTourGuideCubit>().fetchAllTourGuide();
                }
              },

              child: Image.asset(AppAssets.boldEdit),
            ),
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
                                      "years_experience".tr(),
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                  style: AppTextStyles.font14SemiBold.copyWith(
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
                  ],
                ),
                HeightSpace(16.h),
                Text(
                  guideModel.description,
                  style: AppTextStyles.font14Regular.copyWith(
                    color: Color(0xFF4B5565),
                    height: 1.50,
                  ),
                ),
              ],
            ),
          ),
        ),
        SliverToBoxAdapter(child: HeightSpace(16.h)),
        // if
        if (guideModel.social.facebook != null ||
            guideModel.social.instagram != null ||
            guideModel.social.twitter != null ||
            guideModel.social.youtube != null)
          InfoHeader(title: 'contact_methods'.tr(), image: AppAssets.global),

        SliverToBoxAdapter(
          child: Padding(
            padding: EdgeInsets.symmetric(horizontal: 16.0.w),
            child: SocialLinksSection(socialModel: guideModel.social),
          ),
        ),

        SliverToBoxAdapter(child: HeightSpace(16.h)),
        // InfoHeader(
        //   title: "الصور",
        //   image: AppAssets.global,
        //   child: PrimaryButton(
        //     width: 100.w,
        //     height: 46.h,
        //     backgroundColor: AppColors.primaryLight2,
        //     textColor: AppColors.obsidianBlack,

        //     title: " إضافة",
        //     onTap: () {},
        //   ),
        // ),
        SliverToBoxAdapter(child: HeightSpace(16.h)),
        if (guideModel.languages.isNotEmpty)
          InfoHeader(title: "languages".tr(), image: AppAssets.global),
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
                        width: double.infinity,

                        decoration: ShapeDecoration(
                          color: const Color(
                            0xFFFCFCFD,
                          ) /* Color-Neutrals-25 */,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(4),
                          ),
                        ),
                        child: Text(
                          ". " + language.name,
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

        SliverToBoxAdapter(child: HeightSpace(16.h)),
        if (guideModel.regions.isNotEmpty)
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
                                mainAxisSize: MainAxisSize.max,
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    "region_label".tr() + " \t${language.name}",
                                    style: AppTextStyles.font14Regular.copyWith(
                                      color: AppColors.white,
                                    ),
                                  ),
                                  WidthSpace(8.w),
                                  Image.asset(
                                    AppAssets.regionArrow,
                                    width: 16.w,
                                    height: 16.h,
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

        if (guideModel.ratings.isNotEmpty)
          InfoHeader(
            title: "reviews".tr(),
            image: AppAssets.svgMessage,
            subtitle: "reviews_subtitle".tr(),

            child: GestureDetector(
              onTap: () {
                push(
                  RoutesKeys.kSeeAllRatingsList,
                  context,
                  extra: SeeAllRatingsArgs(
                    ratings: guideModel.ratings,
                    type: guideModel.type,
                    id: guideModel.id.toString(),
                  ),
                );
              },
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
      ],
    );
  }
}
