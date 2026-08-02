import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
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
import 'package:hawdaj/features/home/presentation/view/widgets/list_of_supplies_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/offer_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/sliver_section_header.dart';
import 'package:hawdaj/features/places/presentation/view/widgets/map_section.dart';
import 'package:hawdaj/features/rates/presentation/manager/rate_cubit/rate_cubit.dart';
import 'package:hawdaj/features/rates/presentation/view/share_your_rate_button.dart';
import 'package:hawdaj/features/restaurants/presentation/manager/restaurants_cubit/restaurants_details_cubit.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_cubit.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_state.dart';
import 'package:share_plus/share_plus.dart';
import 'dart:ui' as ui;

class RestaurantsDetailsItemsBody extends StatelessWidget {
  const RestaurantsDetailsItemsBody({super.key, required this.restaurants});
  final ZadModel restaurants;
  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    final favoriteCubit = context.read<FavoriteCubit>();
    final RestaurantsDetailsCubit restaurantsDetailsCubit = context
        .read<RestaurantsDetailsCubit>();
    final savedCubit = context.read<SavedCubit>();
    final hasSocialLinks =
        (restaurants.facebookLink?.isNotEmpty ?? false) ||
        (restaurants.instagramLink?.isNotEmpty ?? false) ||
        (restaurants.whatsapp?.isNotEmpty ?? false);
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 8.h),
      child: Stack(
        children: [
          Positioned.fill(
            child: CustomScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              slivers: [
                MultiBlocListener(
                  listeners: [
                    BlocListener<RateCubit, RateState>(
                      listener: (context, state) {
                        if (state is RateAdded) {
                          restaurantsDetailsCubit.getRestaurantsInfo();
                        }
                      },
                    ),
                    BlocListener<FavoriteCubit, FavoriteState>(
                      listener: (context, state) {
                        if (state is FavoriteSuccess) {
                          //getRestaurantsInfo
                          restaurantsDetailsCubit.getRestaurantsInfo();
                          showCustomSuccessToast(state.message);
                        } else if (state is FavoriteError) {
                          showCustomFailureToast(state.error);
                        }
                      },
                    ),
                    BlocListener<SavedCubit, SavedState>(
                      listener: (context, state) {
                        if (state is SavedSuccess) {
                          restaurantsDetailsCubit.getRestaurantsInfo();
                          showCustomSuccessToast(state.message);
                        } else if (state is SavedError) {
                          showCustomFailureToast(state.error);
                        }
                      },
                    ),
                  ],
                  child: CustomAppBarHomeDetails(
                    isSaved: restaurants.isSaved,
                    isFavorite: restaurants.isFavorite,
                    showLocationButton:
                        (restaurants.lat != 0 && restaurants.long != 0)
                        ? true
                        : false,
                    onFavoriteTap: () {
                      shouldExecute(
                        context: context,
                        callback: () {
                          favoriteCubit.addToFavorite(
                            favoriteId: restaurants.id,
                            favoriteType: restaurants.type,
                          );
                        },
                      );
                    },
                    onSaveTap: () {
                      shouldExecute(
                        context: context,
                        callback: () {
                          savedCubit.addToSaved(
                            savedId: restaurants.id,
                            savedType: restaurants.type,
                          );
                        },
                      );
                    },
                    fallbackImageUrl: restaurants.image,
                    galleryImages: restaurants.galleries
                        .map((e) => e.file)
                        .toList(),
                    onShareTap: () {
                      final link =
                          'http://hawdaj.net/ar/restaurants/${restaurants.slug}';
                      final title = restaurants.title ?? '';

                      if (link.isNotEmpty) {
                        Share.share(link, subject: title);
                      } else {
                        debugPrint('Cannot share empty text');
                        showCustomFailureToast('no_share_link'.tr());
                      }
                    },

                    // onLocationTap: () {
                    //   final lat = restaurants.lat;
                    //   final long = restaurants.long;
                    //   if (lat != 0 && long != 0) {
                    //     final googleMapsUrl =
                    //         'https://www.google.com/maps/search/?api=1&query=//,';
                    //     openFileLink(googleMapsUrl);
                    //   } else {
                    //     showCustomFailureToast('الموقع غير متوفر');
                    //   }
                    // },
                    onLocationTap: () {
                      final lat = restaurants.lat;
                      final long = restaurants.long;
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
                  title: restaurants.title,
                  rating: restaurants.rate.toString(),
                  address: restaurants.isOnline == true
                      ? 'store_online'.tr()
                      : '${restaurants.region.name}, ${restaurants.city.name}',
                  description: restaurants.description,
                ),
                SliverToBoxAdapter(child: HeightSpace(16.h)),

                InfoHeader(
                  title: "info_header_zad_list_title".tr(),
                  subtitle: "info_header_zad_list_subtitle".tr(),
                  image: AppAssets.documentText,
                ),

                SliverToBoxAdapter(child: HeightSpace(16.h)),
                SliverToBoxAdapter(child: ListOfSuppliesSection()),
                SliverToBoxAdapter(child: HeightSpace(16.h)),
                if (hasSocialLinks)
                  InfoHeader(
                    title: "contact_methods".tr(),
                    image: AppAssets.global,
                  ),
                SliverToBoxAdapter(
                  child: Wrap(
                    children: [
                      if (restaurants.facebookLink?.isNotEmpty ?? false)
                        SocialLinkItem(
                          label: "Facebook",
                          icon: FontAwesomeIcons.facebook,
                          url: restaurants.facebookLink ?? "",
                        ),

                      if (restaurants.instagramLink?.isNotEmpty ?? false)
                        SocialLinkItem(
                          label: "Instagram",
                          icon: FontAwesomeIcons.instagram,
                          url: restaurants.instagramLink ?? "",
                        ),

                      if (restaurants.whatsapp?.isNotEmpty ?? false)
                        SocialLinkItem(
                          label: "Whats app",
                          icon: FontAwesomeIcons.whatsapp,

                          url:
                              'https://wa.me/${restaurants.whatsapp?.replaceAll('+', '').replaceAll(' ', '')}',
                        ),
                    ],
                  ),
                ),

                SliverToBoxAdapter(child: HeightSpace(16.h)),

                restaurants.isOnline == true
                    ? SliverToBoxAdapter(child: HeightSpace(16.h))
                    : InfoHeader(
                        title: "location".tr(),
                        subtitle: "location_subtitle".tr(),
                        image: AppAssets.info,
                      ),
                restaurants.isOnline == true
                    ? SliverToBoxAdapter(child: HeightSpace(16.h))
                    : MapSection(lat: restaurants.lat, long: restaurants.long),

                SliverToBoxAdapter(child: HeightSpace(16.h)),
                //OffersAvailableItems
                InfoHeader(
                  title: "info_header_offers_title".tr(),
                  subtitle: "info_header_offers_subtitle".tr(),
                  image: AppAssets.ticket,
                ),
                SliverToBoxAdapter(child: HeightSpace(16.h)),
                OfferSection(
                  destLat: restaurants.lat,
                  destLong: restaurants.long,
                ),

                SliverToBoxAdapter(child: HeightSpace(16.h)),
                if (restaurants.ratings.isNotEmpty)
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
                            ratings: restaurants.ratings,
                            type: restaurants.type,
                            id: restaurants.id.toString(),
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

                if (restaurants.ratings.isNotEmpty)
                  SliverToBoxAdapter(
                    child: SizedBox(
                      height: 110.h,
                      child: ListView.separated(
                        scrollDirection: Axis.horizontal,
                        //  padding: const EdgeInsets.symmetric(horizontal: 16),
                        itemCount: restaurants.ratings.length,
                        separatorBuilder: (_, __) => SizedBox(width: 12.w),
                        itemBuilder: (context, index) {
                          final rate = restaurants.ratings[index];
                          return SizedBox(
                            width: 283.w,
                            child: HowdahGuidesSectionItems(
                              title: rate.name,
                              description: rate.rateText,
                              imageUrl: AppAssets.user,
                              rating: rate.rate.toString(),
                              type: restaurants.type,
                              parentId: restaurants.id.toString(),
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
                    type: restaurants.type,
                    parentId: restaurants.id.toString(),
                  ),
                ),
                SliverToBoxAdapter(child: HeightSpace(16.h)),
              ],
            ),
          ),

          //ticket price at bottom
          if (restaurants.ticketLink != null &&
              restaurants.ticketLink!.isNotEmpty)
            BookTicketButtomWidget(ticketLink: restaurants.ticketLink!),
        ],
      ),
    );
  }
}
