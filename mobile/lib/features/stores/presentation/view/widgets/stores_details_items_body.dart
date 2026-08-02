import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart'
    show showCustomSuccessToast;
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/functions/open_file_link.dart';
import 'package:hawdaj/core/utils/should_execute.dart';
import 'package:hawdaj/core/widgets/book_ticket_buttom_widget.dart';

import 'package:hawdaj/features/events/presentation/view/widgets/social_link_item.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_cubit.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_state.dart';
import 'package:hawdaj/features/home/data/model/zad_model/zad_model.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/custom_app_bar_home_details.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/details_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/howdah_guides_section_items.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/sliver_section_header.dart';
import 'package:hawdaj/features/places/presentation/view/widgets/map_section.dart';
import 'package:hawdaj/features/rates/presentation/manager/rate_cubit/rate_cubit.dart';
import 'package:hawdaj/features/rates/presentation/view/share_your_rate_button.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_cubit.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_state.dart';
import 'package:hawdaj/features/stores/presentation/manager/stores_details_cubit/stores_details_cubit.dart';
import 'package:share_plus/share_plus.dart';
import 'dart:ui' as ui;

class StoresDetailsItemsBody extends StatelessWidget {
  const StoresDetailsItemsBody({super.key, required this.store});
  final ZadModel store;
  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    final favoriteCubit = context.read<FavoriteCubit>();
    final StoresDetailsCubit storesDetailsCubit = context
        .read<StoresDetailsCubit>();
    final savedCubit = context.read<SavedCubit>();
    final hasSocialLinks =
        (store.facebookLink?.isNotEmpty ?? false) ||
        (store.instagramLink?.isNotEmpty ?? false) ||
        (store.whatsapp?.isNotEmpty ?? false);
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 8.h),
      child: Stack(
        children: [
          CustomScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            slivers: [
              MultiBlocListener(
                listeners: [
                  BlocListener<FavoriteCubit, FavoriteState>(
                    listener: (context, state) {
                      if (state is FavoriteSuccess) {
                        storesDetailsCubit.getStoresInfo();
                        showCustomSuccessToast(state.message);
                      } else if (state is FavoriteError) {
                        showCustomFailureToast(state.error);
                      }
                    },
                  ),
                  BlocListener<SavedCubit, SavedState>(
                    listener: (context, state) {
                      if (state is SavedSuccess) {
                        storesDetailsCubit.getStoresInfo();
                        showCustomSuccessToast(state.message);
                      } else if (state is SavedError) {
                        showCustomFailureToast(state.error);
                      }
                    },
                  ),
                  BlocListener<RateCubit, RateState>(
                    listener: (context, state) {
                      if (state is RateAdded) {
                        storesDetailsCubit.getStoresInfo();
                      }
                    },
                  ),

                  //add rating
                ],
                child: CustomAppBarHomeDetails(
                  isSaved: store.isSaved,
                  isFavorite: store.isFavorite,
                  galleryImages: store.galleries.map((e) => e.file).toList(),
                  showLocationButton: (store.lat != 0 && store.long != 0)
                      ? true
                      : false,
                  onFavoriteTap: () {
                    shouldExecute(
                      context: context,
                      callback: () {
                        favoriteCubit.addToFavorite(
                          favoriteId: store.id,
                          favoriteType: store.type,
                        );
                      },
                    );
                  },
                  onSaveTap: () {
                    shouldExecute(
                      context: context,
                      callback: () {
                        savedCubit.addToSaved(
                          savedId: store.id,
                          savedType: store.type,
                        );
                      },
                    );
                  },
                  onShareTap: () {
                    final link = 'http://hawdaj.net/ar/stores/${store.slug}';
                    final title = store.title ?? '';

                    if (link.isNotEmpty) {
                      Share.share(link, subject: title);
                    } else {
                      debugPrint('Cannot share empty text');
                      showCustomFailureToast('no_share_link'.tr());
                    }
                  },

                  onLocationTap: () {
                    final lat = store.lat;
                    final long = store.long;
                    if (lat != 0 && long != 0) {
                      final googleMapsUrl =
                          'https://www.google.com/maps/search/?api=1&query=$lat,$long';
                      openFileLink(googleMapsUrl);
                    } else {
                      showCustomFailureToast("location_unavailable".tr());
                    }
                  },
                ),
              ),
              SliverToBoxAdapter(child: HeightSpace(16.h)),
              DetailsSection(
                title: store.title,
                rating: store.rate.toString(),
                address: store.isOnline == true
                    ? 'store_online'.tr()
                    : '${store.region.name}, ${store.city.name}',
                description: store.description,
              ),
              if (hasSocialLinks)
                // InfoHeader(title: "contact_methods".tr(), image: AppAssets.info),
                SliverToBoxAdapter(
                  child: SizedBox(
                    width: 361,
                    child: Text(
                      "contact_methods".tr(),
                      style: TextStyle(
                        color: Colors.black,
                        fontSize: 20,
                        fontFamily: 'The Year of The Camel',
                        fontWeight: FontWeight.w900,
                        height: 1.20,
                      ),
                    ),
                  ),
                ),
              SliverToBoxAdapter(child: HeightSpace(8.h)),
              SliverToBoxAdapter(
                child: Wrap(
                  children: [
                    if (store.facebookLink?.isNotEmpty ?? false)
                      SocialLinkItem(
                        label: "Facebook",
                        icon: FontAwesomeIcons.facebook,
                        url: store.facebookLink ?? "",
                      ),

                    if (store.instagramLink?.isNotEmpty ?? false)
                      SocialLinkItem(
                        label: "Instagram",
                        icon: FontAwesomeIcons.instagram,
                        url: store.instagramLink ?? "",
                      ),

                    if (store.whatsapp?.isNotEmpty ?? false)
                      SocialLinkItem(
                        label: "Whats app",
                        icon: FontAwesomeIcons.whatsapp,
                        url:
                            'https://wa.me/${store.whatsapp?.replaceAll('+', '').replaceAll(' ', '')}',
                      ),
                    if (store.websiteLink?.isNotEmpty ?? false)
                      SocialLinkItem(
                        label: "Website",
                        icon: FontAwesomeIcons.globe,
                        url: store.websiteLink ?? "",
                      ),
                    //https://x.com/Majaz_saq
                    if (store.twitterLink?.isNotEmpty ?? false)
                      SocialLinkItem(
                        label: "Twitter",
                        icon: FontAwesomeIcons.twitter,
                        url: store.twitterLink ?? "",
                      ),
                  ],
                ),
              ),
              SliverToBoxAdapter(child: HeightSpace(16.h)),
              store.isOnline == true
                  ? SliverToBoxAdapter(child: HeightSpace(16.h))
                  : InfoHeader(
                      title: "location".tr(),
                      subtitle: "location_subtitle".tr(),
                      image: AppAssets.info,
                    ),
              store.isOnline == true
                  ? SliverToBoxAdapter(child: HeightSpace(16.h))
                  : MapSection(lat: store.lat, long: store.long),

              SliverToBoxAdapter(child: HeightSpace(16.h)),
              if (store.ratings.isNotEmpty)
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
                          ratings: store.ratings,
                          type: store.type,
                          id: store.id.toString(),
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
              if (store.ratings.isNotEmpty)
                SliverToBoxAdapter(
                  child: SizedBox(
                    height: 110.h,
                    child: ListView.separated(
                      scrollDirection: Axis.horizontal,
                      //  padding: const EdgeInsets.symmetric(horizontal: 16),
                      itemCount: store.ratings.length,
                      separatorBuilder: (_, __) => SizedBox(width: 12.w),
                      itemBuilder: (context, index) {
                        final rate = store.ratings[index];
                        return SizedBox(
                          width: 283.w,
                          child: HowdahGuidesSectionItems(
                            title: rate.name,
                            description: rate.rateText,
                            imageUrl: AppAssets.user,
                            rating: rate.rate.toString(),
                            type: store.type,
                            parentId: store.id.toString(),
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
                  type: store.type,
                  parentId: store.id.toString(),
                ),
              ),
              SliverToBoxAdapter(child: HeightSpace(16.h)),
            ],
          ),
          //TODO  placeBookButton
          if (store.ticketLink != null && store.ticketLink!.isNotEmpty)
            BookTicketButtomWidget(ticketLink: store.ticketLink!),
        ],
      ),
    );
  }
}
