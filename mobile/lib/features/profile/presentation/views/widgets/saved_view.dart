import 'dart:ui' as ui;
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
import 'package:hawdaj/features/profile/presentation/manager/get_favorites_cubit/get_favorites_cubit.dart';
import 'package:hawdaj/features/profile/presentation/manager/get_saved_cubit/get_saved_cubit.dart';
import 'package:hawdaj/features/saved/presentation/manager/saved_cubit.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_places_item_card.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';

class SavedView extends StatelessWidget {
  const SavedView({super.key});

  @override
  Widget build(BuildContext context) {
    final savedCubit = context.read<SavedCubit>();

    return Scaffold(
      body: Column(
        children: [
          TripStartAppBar(title: "saved_items".tr(), showimage: false),

          Expanded(
            child: BlocBuilder<GetSavedCubit, GetSavedState>(
              builder: (context, state) {
                if (state is GetSavedLoading) {
                  return const Center(child: CircularProgressIndicator());
                } else if (state is GetSavedFailure) {
                  return Center(
                    child: Text(
                      state.errMessage,
                      style: AppTextStyles.font16Medium.copyWith(
                        color: Colors.red,
                      ),
                    ),
                  );
                } else if (state is GetSavedSuccess) {
                  if (state.savedItems.isEmpty) {
                    return Center(child: Text("no_saved_items".tr()));
                  }

                  return RefreshIndicator(
                    onRefresh: () async {
                      await context.read<GetSavedCubit>().getSaved();
                    },
                    child: ListView.separated(
                      padding: EdgeInsets.all(16.w),
                      itemCount: state.savedItems.length,
                      separatorBuilder: (_, __) => SizedBox(height: 12.h),
                      itemBuilder: (context, index) {
                        final item = state.savedItems[index];
                        final placeModel = mapItemGlodalToUnifiedSaved(item);
                        return TasneefPlacesItemCard(
                          isFavorite: false,
                          place: placeModel,

                          goToDetails: () async {
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

                            /// ننتظر نتيجة التنقل
                            final result = await push(
                              route,
                              context,
                              extra: extra,
                            );

                            /// لو رجعنا بقيمة true نعمل reload
                            if (result == true) {
                              context
                                  .read<GetSavedCubit>()
                                  .getSaved(); // أو أي دالة عندك لإعادة تحميل البيانات
                            }
                          },

                          child: GestureDetector(
                            onTap: () async {
                              savedCubit.addToSaved(
                                savedId: item.savedId,
                                savedType: placeModel.type,
                              );
                              await context.read<GetSavedCubit>().getSaved();
                            },
                            child: SvgPicture.asset(AppAssets.activeSave),
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
                    child: Text("load_saved".tr()),
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
