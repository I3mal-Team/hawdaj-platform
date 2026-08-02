import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart' show push;
import 'package:hawdaj/core/routing/routes.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/functions/open_file_link.dart';
import 'package:hawdaj/core/utils/should_execute.dart';
import 'package:hawdaj/core/widgets/book_ticket_buttom_widget.dart';
import 'package:hawdaj/features/events/presentation/manager/events_details_cubit/events_details_cubit.dart';
import 'package:hawdaj/features/events/presentation/view/widgets/social_link_item.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_cubit.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_state.dart';
import 'package:hawdaj/features/home/data/model/top_events_model/event_model.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/custom_app_bar_home_details.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/details_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/howdah_guides_section_items.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/offer_date_range.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/sliver_section_header.dart';
import 'package:hawdaj/features/places/presentation/view/widgets/map_section.dart';
import 'package:hawdaj/features/rates/presentation/manager/rate_cubit/rate_cubit.dart';
import 'package:hawdaj/features/rates/presentation/view/share_your_rate_button.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_cubit.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_state.dart';
import 'package:share_plus/share_plus.dart';
import 'dart:ui' as ui;

class EventsDetailsItemsBody extends StatelessWidget {
  const EventsDetailsItemsBody({super.key, required this.event});
  final EventModel event;
  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    final favoriteCubit = context.read<FavoriteCubit>();
    final EventsDetailsCubit eventsDetailsCubit = context
        .read<EventsDetailsCubit>();
    final savedCubit = context.read<SavedCubit>();

    final hasSocialLinks =
        (event.facebook.isNotEmpty ?? false) ||
        (event.linkedin.isNotEmpty ?? false) ||
        (event.instagram.isNotEmpty ?? false) ||
        (event.whatsapp.isNotEmpty ?? false) ||
        (event.website?.isNotEmpty ?? false);

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
                          //getEventsInfo
                          eventsDetailsCubit.getEventsInfo();
                          showCustomSuccessToast(state.message);
                        } else if (state is FavoriteError) {
                          showCustomFailureToast(state.error);
                        }
                      },
                    ),
                    BlocListener<SavedCubit, SavedState>(
                      listener: (context, state) {
                        if (state is SavedSuccess) {
                          eventsDetailsCubit.getEventsInfo();
                          showCustomSuccessToast(state.message);
                        } else if (state is SavedError) {
                          showCustomFailureToast(state.error);
                        }
                      },
                    ),
                    BlocListener<RateCubit, RateState>(
                      listener: (context, state) {
                        if (state is RateAdded) {
                          eventsDetailsCubit.getEventsInfo();
                        }
                      },
                    ),
                  ],
                  child: CustomAppBarHomeDetails(
                    isSaved: event.isSaved,
                    isFavorite: event.isFavorite,
                    fallbackImageUrl: event.image,
                    showLocationButton: (event.lat != 0 && event.long != 0)
                        ? true
                        : false,
                    onFavoriteTap: () {
                      shouldExecute(
                        context: context,
                        callback: () {
                          favoriteCubit.addToFavorite(
                            favoriteId: event.id,
                            favoriteType: event.type,
                          );
                        },
                      );
                    },
                    onSaveTap: () {
                      shouldExecute(
                        context: context,
                        callback: () {
                          savedCubit.addToSaved(
                            savedId: event.id,
                            savedType: event.type,
                          );
                        },
                      );
                    },
                    galleryImages: event.galleries.map((e) => e.file).toList(),

                    onShareTap: () {
                      Share.share(
                        'http://hawdaj.net/ar/events/event-details/${event.slug}',
                        subject: 'Nice Service',
                      );
                    },
                    onLocationTap: () {
                      final lat = event.lat;
                      final long = event.long;
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
                  title: event.title,
                  button: event.closed,

                  address:
                      '${event.region?.name ?? ""}, ${event.city?.name ?? ""}',
                  description: event.description,
                ),
                SliverToBoxAdapter(child: HeightSpace(16.h)),
                SliverToBoxAdapter(
                  child: Container(
                    width: double.infinity,
                    padding: const EdgeInsets.symmetric(
                      horizontal: 8,
                      vertical: 12,
                    ),
                    decoration: ShapeDecoration(
                      color: const Color(0xFFFCFCFD) /* Color-Neutrals-25 */,
                      shape: RoundedRectangleBorder(
                        side: BorderSide(
                          width: 1,
                          color: const Color(
                            0xFFF8FAFC,
                          ) /* Color-Neutrals-50 */,
                        ),
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                    child: OfferDateRange(
                      startDate: event.fromDate,
                      endDate: event.toDate,
                      iconColorOne: AppColors.successGreen,
                      iconColorTwo: AppColors.redcolor,
                    ),
                  ),
                ),
                SliverToBoxAdapter(child: HeightSpace(16.h)),
                if (hasSocialLinks)
                  InfoHeader(
                    title: 'contact_methods'.tr(),
                    image: AppAssets.global,
                  ),

                if (hasSocialLinks)
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: EdgeInsets.symmetric(
                        horizontal: 16.w,
                        vertical: 8.h,
                      ),
                      child: Wrap(
                        children: [
                          if (event.facebook.isNotEmpty)
                            SocialLinkItem(
                              label: "Facebook",
                              icon: FontAwesomeIcons.facebook,
                              url: event.facebook,
                            ),

                          if (event.linkedin.isNotEmpty)
                            SocialLinkItem(
                              label: "LinkedIn",
                              icon: FontAwesomeIcons.linkedin,
                              url: event.linkedin,
                            ),

                          if (event.instagram.isNotEmpty)
                            SocialLinkItem(
                              label: "Instagram",
                              icon: FontAwesomeIcons.instagram,
                              url: event.instagram,
                            ),

                          if (event.whatsapp.isNotEmpty)
                            SocialLinkItem(
                              label: "WhatsApp",
                              icon: FontAwesomeIcons.whatsapp,
                              url:
                                  'https://wa.me/${event.whatsapp.replaceAll("+", "").replaceAll(" ", "")}',
                            ),

                          if (event.website?.isNotEmpty ?? false)
                            SocialLinkItem(
                              label: "Website",
                              icon: FontAwesomeIcons.globe,
                              url: event.website!,
                            ),
                        ],
                      ),
                    ),
                  ),

                SliverToBoxAdapter(child: HeightSpace(16.h)),
                InfoHeader(
                  title: "location".tr(),
                  subtitle: "location_subtitle".tr(),
                  image: AppAssets.info,
                ),

                MapSection(lat: event.lat, long: event.long),

                SliverToBoxAdapter(child: HeightSpace(16.h)),
                if (event.ratings.isNotEmpty)
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
                            ratings: event.ratings,
                            type: event.type,
                            id: event.id.toString(),
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
                if (event.ratings.isNotEmpty)
                  SliverToBoxAdapter(
                    child: SizedBox(
                      height: 110.h,
                      child: ListView.separated(
                        scrollDirection: Axis.horizontal,
                        //  padding: const EdgeInsets.symmetric(horizontal: 16),
                        itemCount: event.ratings.length,
                        separatorBuilder: (_, __) => SizedBox(width: 12.w),
                        itemBuilder: (context, index) {
                          final rate = event.ratings[index];
                          return SizedBox(
                            width: 283.w,
                            child: HowdahGuidesSectionItems(
                              title: rate.name,
                              description: rate.rateText,
                              imageUrl: AppAssets.user,
                              rating: rate.rate.toString(),
                              type: event.type,
                              parentId: event.id.toString(),
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
                    type: event.type,
                    parentId: event.id.toString(),
                  ),
                ),
                SliverToBoxAdapter(child: HeightSpace(100.h)),
              ],
            ),
          ),
          if (event.ticketLink != null && event.ticketLink!.isNotEmpty)
            BookTicketButtomWidget(ticketLink: event.ticketLink!),
        ],
      ),
    );
  }
}
