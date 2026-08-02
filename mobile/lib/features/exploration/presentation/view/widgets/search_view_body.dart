import 'dart:async' show Timer;
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/search_filed.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/should_execute.dart';
import 'package:hawdaj/core/views/city_dropdown.dart';
import 'package:hawdaj/core/views/region_drop_down_field.dart';
import 'package:hawdaj/features/exploration/data/model/glodal_search/item.dart';
import 'package:hawdaj/features/exploration/presentation/manager/get_search_global_cubit/get_search_global_cubit.dart';
import 'package:hawdaj/features/exploration/presentation/manager/search_filter_cubit/search_filter_cubit.dart';
import 'package:hawdaj/features/exploration/presentation/view/map_view.dart';
import 'package:hawdaj/features/exploration/presentation/view/widgets/map_item_glodal_to_unified_place.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_cubit.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_state.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_gradient_background.dart';

import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_places_item_card.dart'
    show TasneefPlacesItemCard;
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';
import 'package:url_launcher/url_launcher.dart';
import 'dart:ui' as ui;

class SearchViewBody extends StatefulWidget {
  const SearchViewBody({super.key});

  @override
  State<SearchViewBody> createState() => _SearchViewBodyState();
}

class _SearchViewBodyState extends State<SearchViewBody> {
  late final TextEditingController _searchController;
  Timer? _debounce;

  @override
  void initState() {
    super.initState();
    final initText = context.read<SearchFilterCubit>().state.searchText;
    _searchController = TextEditingController(text: initText);
    _searchController.addListener(_onSearchChanged);
  }

  void _onSearchChanged() {
    _debounce?.cancel();
    _debounce = Timer(const Duration(milliseconds: 350), () {
      context.read<SearchFilterCubit>().updateSearch(_searchController.text);
    });
  }

  @override
  void dispose() {
    _debounce?.cancel();
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    final topPadding = MediaQuery.of(context).padding.top;
    final searchFilterCubit = context.read<SearchFilterCubit>();
    final pagingController = context
        .read<GetSearchGlobalCubit>()
        .pagingController;

    return BlocBuilder<SearchFilterCubit, SearchFilterState>(
      buildWhen: (prev, curr) =>
          prev.regionId != curr.regionId ||
          prev.cityId != curr.cityId ||
          prev.searchText != curr.searchText ||
          prev.shouldSearch != curr.shouldSearch,
      builder: (context, state) {
        // حافظ مزامنة نص البحث من الكيوبت للكونترولر بدون “نطّة” المؤشر
        if (_searchController.text != state.searchText) {
          _searchController.value = _searchController.value.copyWith(
            text: state.searchText,
            selection: TextSelection.collapsed(offset: state.searchText.length),
            composing: TextRange.empty,
          );
        }

        return BlocListener<FavoriteCubit, FavoriteState>(
          listener: (context, favState) {
            if (favState is FavoriteSuccess) pagingController.refresh();
          },
          child: CustomScrollView(
            physics: const BouncingScrollPhysics(
              parent: AlwaysScrollableScrollPhysics(),
            ),
            keyboardDismissBehavior: ScrollViewKeyboardDismissBehavior.onDrag,
            slivers: [
              SliverAppBar(
                pinned: true,
                elevation: 0,

                toolbarHeight: 56,
                expandedHeight: 160.h,
                // pinned: true,
                // backgroundColor: Colors.transparent,
                automaticallyImplyLeading: false,
                flexibleSpace: FlexibleSpaceBar(
                  background: Stack(
                    children: [
                      const AppBarGradientBackground(),
                      Positioned(
                        top: topPadding,
                        child: IconButton(
                          onPressed: () => Navigator.pop(context),
                          icon: Transform(
                            alignment: Alignment.center,
                            transform: Matrix4.rotationY(isRtl ? 0 : 3.1416),
                            child: SvgPicture.asset(AppAssets.arrow),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                // centerTitle: false,
                title: Center(
                  child: Text(
                    'search'.tr(),
                    textDirection: ui.TextDirection.rtl,
                    style: AppTextStyles.font24SemiBold.copyWith(
                      color: Colors.white,
                    ),
                  ),
                ),
                bottom: PreferredSize(
                  preferredSize: const Size.fromHeight(64),
                  child: Padding(
                    padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
                    child: SearchFiled(
                      controller: _searchController,
                      hintText: "search_anything".tr(),
                      suffix: _searchController.text.isEmpty
                          ? const SizedBox()
                          : GestureDetector(
                              onTap: () {
                                _searchController.clear();
                                searchFilterCubit.clearSearch();
                              },
                              child: Padding(
                                padding: const EdgeInsets.all(12.0),
                                child: SvgPicture.asset(AppAssets.closeCircle),
                              ),
                            ),
                    ),
                  ),
                ),
              ),

              SliverPadding(
                padding: const EdgeInsets.symmetric(
                  horizontal: 16,
                  vertical: 12,
                ),
                sliver: SliverToBoxAdapter(
                  child: Row(
                    children: [
                      Expanded(
                        child: RegionDropdown(
                          selectedRegionId: state.regionId,
                          onChanged: searchFilterCubit.updateRegion,
                          onClear: searchFilterCubit.clearRegion,
                          hint: 'choose_regions_hint'.tr(),
                        ),
                      ),
                      WidthSpace(8.w),
                      Expanded(
                        child: CityDropdown(
                          selectedCityId: state.cityId,
                          onChanged: searchFilterCubit.updateCity,
                          onClear: searchFilterCubit.clearCity,
                          hint: 'choose_city'.tr(),
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              // ================== قائمة النتائج (PagedSliverList) ==================
              SliverPadding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                sliver: PagedSliverList<int, ItemGlodal>(
                  pagingController: pagingController,
                  builderDelegate: PagedChildBuilderDelegate<ItemGlodal>(
                    itemBuilder: (context, item, index) {
                      final placeModel = mapItemGlodalToUnifiedPlace(item);
                      return Padding(
                        padding: const EdgeInsets.only(bottom: 12),
                        child: SizedBox(
                          width: 289.w,
                          child: TasneefPlacesItemCard(
                            place: placeModel,
                            onTapFavorite: () {
                              final cubit = context.read<FavoriteCubit>();
                              shouldExecute(
                                context: context,
                                callback: () {
                                  cubit.addToFavorite(
                                    favoriteId: placeModel.id,
                                    favoriteType: placeModel.type,
                                  );
                                },
                              );
                            },
                            goToDetails: () {
                              String route;
                              dynamic extra;

                              switch (item.type) {
                                case 'store':
                                  route = RoutesKeys.kStoresDetailsItems;
                                  extra = item.slug;
                                  break;
                                case 'place':
                                  route = RoutesKeys.kPlacesDetailsItems;
                                  extra = item.slug;
                                  break;
                                case 'restaurant':
                                  route = RoutesKeys.kRestaurantsDetailsItems;
                                  extra = {'slug': item.slug, 'id': item.id};
                                  break;
                                case 'event':
                                  route = RoutesKeys.kEventsDetailsView;
                                  extra = item.slug;
                                  break;
                                case 'story':
                                  route = RoutesKeys.kStoriesDetailsView;
                                  extra = item.slug;
                                  break;
                                case 'zad':
                                  route = RoutesKeys.kRestaurantsDetailsItems;
                                  extra = {'slug': item.slug, 'id': item.id};
                                  break;
                                default:
                                  route = RoutesKeys.kPlacesDetailsItems;
                                  extra = item.slug;
                              }

                              push(route, context, extra: extra);
                            },

                            child: GestureDetector(
                              onTap: () async {
                                final lat = item.lat;
                                final long = item.long;
                                final url = Uri.parse(
                                  "https://www.google.com/maps/search/?api=1&query=$lat,$long",
                                );
                                if (await canLaunchUrl(url)) {
                                  await launchUrl(
                                    url,
                                    mode: LaunchMode.externalApplication,
                                  );
                                } else {
                                  debugPrint("❌ لا يمكن فتح خرائط جوجل");
                                }
                              },
                              child: SvgPicture.asset(AppAssets.locationPin),
                            ),
                          ),
                        ),
                      );
                    },
                    noItemsFoundIndicatorBuilder: (_) => Column(
                      children: [
                        Image.asset(AppAssets.emptySearch),
                        const SizedBox(height: 8),
                        Text("no_results".tr()),
                      ],
                    ),
                    firstPageErrorIndicatorBuilder: (_) => Column(
                      children: [
                        Image.asset(AppAssets.emptySearch),
                        const SizedBox(height: 8),
                        Text("load_more_error".tr()),
                        PrimaryButton(
                          title: "retry".tr(),
                          onTap: () => pagingController.refresh(),
                        ),
                      ],
                    ),
                    newPageErrorIndicatorBuilder: (_) =>
                        Center(child: Text("load_more_error".tr())),
                  ),
                ),
              ),

              const SliverToBoxAdapter(child: SizedBox(height: 16)),
            ],
          ),
        );
      },
    );
  }
}
