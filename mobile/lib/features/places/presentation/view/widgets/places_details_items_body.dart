import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart'
    show FontAwesomeIcons;
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
import 'package:hawdaj/features/home/presentation/view/widgets/custom_app_bar_home_details.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/details_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/howdah_guides_section_items.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/properties_grid.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/sliver_section_header.dart';
import 'package:hawdaj/features/places/presentation/manager/cubit/place_details_cubit.dart';
import 'package:hawdaj/features/places/presentation/view/widgets/map_section.dart';
import 'package:hawdaj/features/rates/presentation/manager/rate_cubit/rate_cubit.dart';
import 'package:hawdaj/features/rates/presentation/view/share_your_rate_button.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_cubit.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_state.dart';
import 'package:share_plus/share_plus.dart';
import 'dart:ui' as ui;

class PlacesDetailsItemsBody extends StatelessWidget {
  const PlacesDetailsItemsBody({super.key, required this.place});
  final UnifiedPlaceModel place;
  @override
  Widget build(BuildContext context) {
    final favoriteCubit = context.read<FavoriteCubit>();
    final savedCubit = context.read<SavedCubit>();
    final placeDetailsCubit = context.read<PlaceDetailsCubit>();
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    final hasSocialLinks =
        (place.facebookLink?.isNotEmpty ?? false) ||
        (place.instagramLink?.isNotEmpty ?? false) ||
        (place.whatsapp?.isNotEmpty ?? false);

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
                    BlocListener<FavoriteCubit, FavoriteState>(
                      listener: (context, state) {
                        if (state is FavoriteSuccess) {
                          placeDetailsCubit.getPlaceInfo();
                          showCustomSuccessToast(state.message);
                        } else if (state is FavoriteError) {
                          showCustomFailureToast(state.error);
                        }
                      },
                    ),
                    BlocListener<SavedCubit, SavedState>(
                      listener: (context, state) {
                        if (state is SavedSuccess) {
                          placeDetailsCubit.getPlaceInfo();
                          showCustomSuccessToast(state.message);
                        } else if (state is SavedError) {
                          showCustomFailureToast(state.error);
                        }
                      },
                    ),
                    BlocListener<RateCubit, RateState>(
                      listener: (context, state) {
                        if (state is RateAdded) {
                          placeDetailsCubit.getPlaceInfo();
                        }
                      },
                    ),
                  ],
                  child: CustomAppBarHomeDetails(
                    isSaved: place.isSaved,
                    isFavorite: place.isFavorite,
                    galleryImages: place.galleries.map((e) => e.file).toList(),

                    onFavoriteTap: () {
                      shouldExecute(
                        context: context,
                        callback: () {
                          favoriteCubit.addToFavorite(
                            favoriteId: place.id,
                            favoriteType: place.type,
                          );
                        },
                      );
                    },
                    fallbackImageUrl: place.image,
                    onShareTap: () {
                      final link =
                          'http://hawdaj.net/ar/places/details/${place.slug}';
                      final title = place.title;

                      if (link.isNotEmpty) {
                        Share.share(link, subject: title);
                      } else {
                        debugPrint('Cannot share empty text');
                        showCustomFailureToast('no_share_link'.tr());
                      }
                    },

                    onSaveTap: () {
                      shouldExecute(
                        context: context,
                        callback: () {
                          savedCubit.addToSaved(
                            savedId: place.id,
                            savedType: place.type,
                          );
                        },
                      );
                    },
                    showLocationButton: (place.lat != 0 && place.long != 0)
                        ? true
                        : false,
                    onLocationTap: () {
                      final lat = place.lat;
                      final long = place.long;
                      if (lat != 0 && long != 0) {
                        final googleMapsUrl =
                            'https://www.google.com/maps/search/?api=1&query=$lat,$long';
                        openFileLink(googleMapsUrl);
                      } else {
                        showCustomFailureToast('location_unavailable'.tr());
                      }
                    },
                  ),
                ),
                SliverToBoxAdapter(child: HeightSpace(16.h)),
                DetailsSection(
                  title: place.title,
                  rating: place.rate.toString(),
                  address: place.locationText,
                  // address: '${place.region.name}, ${place.city.name}',
                  description: place.description,
                ),
                SliverToBoxAdapter(child: HeightSpace(16.h)),

                InfoHeader(
                  title: "features".tr(),

                  // Ahmed: مش موجود
                  subtitle:
                      "  ${"created_at".tr()}: ${formatArabicDate(place.createdAt ?? "", context: context)}",
                  image: AppAssets.calendarSvg,
                ),

                PropertiesGrid(
                  properties: [
                    PlaceProperty(
                      icon: AppAssets.archive,
                      title: 'property_category'.tr(),
                      value: place.categories.isNotEmpty
                          ? (place.categories.first.name ??
                                "not_specified".tr())
                          : "not_specified".tr(),
                    ),
                    PlaceProperty(
                      icon: AppAssets.coin,
                      title: 'property_price'.tr(),
                      value: place.price?.name ?? "not_specified".tr(),
                    ),
                    PlaceProperty(
                      icon: AppAssets.calendar2,
                      title: 'property_seasons'.tr(),
                      value: place.seasons.isNotEmpty
                          ? place.seasons.join(', ')
                          : "not_specified".tr(),
                    ),
                    PlaceProperty(
                      icon: AppAssets.ticketIcon,
                      title: 'property_tickets'.tr(),
                      value: place.ticketLink != null
                          ? 'yes'.tr()
                          : 'not_required'.tr(),
                    ),
                  ],
                ),

                SliverToBoxAdapter(child: HeightSpace(16.h)),
                if (hasSocialLinks)
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
                      if (place.facebookLink?.isNotEmpty ?? false)
                        SocialLinkItem(
                          label: "Facebook",
                          icon: FontAwesomeIcons.facebook,
                          url: place.facebookLink ?? "",
                        ),

                      if (place.instagramLink?.isNotEmpty ?? false)
                        SocialLinkItem(
                          label: "Instagram",
                          icon: FontAwesomeIcons.instagram,
                          url: place.instagramLink ?? "",
                        ),

                      if (place.whatsapp?.isNotEmpty ?? false)
                        SocialLinkItem(
                          label: "Whats app",
                          icon: FontAwesomeIcons.whatsapp,
                          url:
                              'https://wa.me/${place.whatsapp?.replaceAll('+', '').replaceAll(' ', '')}',
                        ),
                    ],
                  ),
                ),

                SliverToBoxAdapter(child: HeightSpace(16.h)),

                InfoHeader(
                  title: "location".tr(),
                  subtitle: "location_subtitle".tr(),
                  image: AppAssets.info,
                ),
                MapSection(lat: place.lat, long: place.long),

                SliverToBoxAdapter(child: HeightSpace(16.h)),
                if (place.ratings.isNotEmpty)
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
                            ratings: place.ratings,
                            type: place.type,
                            id: place.id.toString(),
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
                if (place.ratings.isNotEmpty)
                  SliverToBoxAdapter(
                    child: SizedBox(
                      height: 110.h,
                      child: ListView.separated(
                        scrollDirection: Axis.horizontal,
                        //  padding: const EdgeInsets.symmetric(horizontal: 16),
                        itemCount: place.ratings.length,
                        separatorBuilder: (_, __) => SizedBox(width: 12.w),
                        itemBuilder: (context, index) {
                          final rate = place.ratings[index];
                          return SizedBox(
                            width: 283.w,
                            child: HowdahGuidesSectionItems(
                              title: rate.name,
                              description: rate.rateText,
                              imageUrl: AppAssets.user,
                              rating: rate.rate,
                              type: place.type,
                              parentId: place.id.toString(),
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
                    type: place.type,
                    parentId: place.id.toString(),
                  ),
                ),
                SliverToBoxAdapter(child: HeightSpace(16.h)),
              ],
            ),
          ),
          if (place.ticketLink != null && place.ticketLink!.isNotEmpty)
            BookTicketButtomWidget(ticketLink: place.ticketLink!),
        ],
      ),
    );
  }
}

String formatArabicDate(String isoDate, {BuildContext? context}) {
  final date = DateTime.tryParse(isoDate);
  if (date == null) return isoDate;

  const months = [
    'يناير',
    'فبراير',
    'مارس',
    'أبريل',
    'مايو',
    'يونيو',
    'يوليو',
    'أغسطس',
    'سبتمبر',
    'أكتوبر',
    'نوفمبر',
    'ديسمبر',
  ];

  return '${date.day} ${months[date.month - 1]} ${date.year}';
}
