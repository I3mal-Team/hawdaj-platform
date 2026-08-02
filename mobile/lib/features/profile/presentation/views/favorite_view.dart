import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/exploration/presentation/view/widgets/map_item_glodal_to_unified_place.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_cubit.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_gradient_background.dart';
import 'dart:ui' as ui;

import 'package:hawdaj/features/profile/presentation/manager/get_favorites_cubit/get_favorites_cubit.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_places_item_card.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';
import 'package:url_launcher/url_launcher.dart';

class FavoriteView extends StatelessWidget {
  const FavoriteView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        children: [
          TripStartAppBar(title: "favorites_title".tr(), showimage: false),

          Expanded(
            child: BlocBuilder<GetFavoritesCubit, GetFavoritesState>(
              builder: (context, state) {
                if (state is GetFavoritesLoading) {
                  return const Center(child: CircularProgressIndicator());
                } else if (state is GetFavoritesFailure) {
                  return Center(
                    child: Text(
                      state.errMessage,
                      style: AppTextStyles.font16Medium.copyWith(
                        color: Colors.red,
                      ),
                    ),
                  );
                } else if (state is GetFavoritesSuccess) {
                  if (state.favorites.isEmpty) {
                    return Center(
                      child: Text(
                        "no_favorites".tr(),
                        style: AppTextStyles.font16Medium,
                      ),
                    );
                  }

                  return RefreshIndicator(
                    onRefresh: () async {
                      await context.read<GetFavoritesCubit>().getFavorites();
                    },
                    child: ListView.separated(
                      padding: EdgeInsets.all(16.w),
                      itemCount: state.favorites.length,
                      separatorBuilder: (_, __) => SizedBox(height: 12.h),
                      itemBuilder: (context, index) {
                        final item = state.favorites[index];
                        final placeModel = mapItemGlodalToUnifiedFavorite(item);
                        return TasneefPlacesItemCard(
                          isFavorite: true,
                          place: placeModel,
                          goToDetails: () {
                            String route;
                            dynamic extra;

                            switch (placeModel.type) {
                              case 'store':
                                route = RoutesKeys.kStoresDetailsItems;
                                extra = placeModel.slug;
                                break;
                              case 'place':
                                route = RoutesKeys.kPlacesDetailsItems;
                                extra = placeModel.slug;
                                break;
                              case 'restaurant':
                                route = RoutesKeys.kRestaurantsDetailsItems;
                                extra = {
                                  'slug': placeModel.slug,
                                  'id': item.id,
                                };
                                break;
                              case 'event':
                                route = RoutesKeys.kEventsDetailsView;
                                extra = placeModel.slug;
                                break;
                              case 'story':
                                route = RoutesKeys.kStoriesDetailsView;
                                extra = placeModel.slug;
                                break;
                              case 'zad':
                                route = RoutesKeys.kRestaurantsDetailsItems;
                                extra = {
                                  'slug': placeModel.slug,
                                  'id': item.id,
                                };
                                break;
                              default:
                                route = RoutesKeys.kPlacesDetailsItems;
                                extra = placeModel.slug;
                            }

                            push(route, context, extra: extra);
                          },
                          onTapFavorite: () async {
                            final cubit = context.read<FavoriteCubit>();
                            await cubit.addToFavorite(
                              favoriteId: item.favoritableId,
                              favoriteType: placeModel.type,
                            );
                            await context
                                .read<GetFavoritesCubit>()
                                .getFavorites();
                          },
                          child: GestureDetector(
                            onTap: () async {
                              final lat = placeModel.lat;
                              final long = placeModel.long;

                              final url = Uri.parse(
                                "https://www.google.com/maps/search/?api=1&query=$lat,$long",
                              );

                              if (await canLaunchUrl(url)) {
                                await launchUrl(
                                  url,
                                  mode: LaunchMode.externalApplication,
                                );
                              } else {
                                print("❌ ${"google_maps_error".tr()}");
                              }
                            },
                            child: SvgPicture.asset(AppAssets.locationPin),
                          ),
                        );
                      },
                    ),
                  );
                }

                return Center(
                  child: ElevatedButton(
                    onPressed: () =>
                        context.read<GetFavoritesCubit>().getFavorites(),
                    child: Text("load_favorites".tr()),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}
