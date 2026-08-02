import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart' hide BackButton;
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:get_it/get_it.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/global_inner_pages_wrapper.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/should_execute.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_cubit.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_state.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_places_item_card.dart';
import 'package:hawdaj/features/home/data/model/explore_category_model.dart';
import '../../domain/repositories/tasneef_repository.dart';
import '../cubits/places_cubit/places_cubit.dart';
import '../cubits/places_cubit/places_state.dart';

class TasneefPlacesListView extends StatelessWidget {
  final int? categoryId;
  final bool? topFeatured;
  final String? type; // 'places', 'stores', 'zads'
  final String? title;
  final bool? manor;

  const TasneefPlacesListView({
    super.key,
    this.categoryId,
    this.topFeatured,
    this.type,
    this.title,
    this.manor,
  });

  @override
  Widget build(BuildContext context) {
    final itemType = type ?? 'places';
    final isManor = manor ?? (itemType == 'stores' || itemType == 'events');

    return BlocProvider(
      create: (context) =>
          PlacesCubit(tasneefRepository: GetIt.instance<TasneefRepository>())
            ..getItemsByType(
              type: itemType,
              perPage: 12,
              categoryId: categoryId,
              topFeatured: topFeatured,
              topStores: itemType == 'stores' ? true : null,
              showNearest: itemType == 'zads' ? false : null,
            ),
      child: _TasneefPlacesListViewBody(
        categoryId: categoryId,
        topFeatured: topFeatured,
        type: itemType,
        title: title,
        manor: isManor,
      ),
    );
  }
}

class _TasneefPlacesListViewBody extends StatefulWidget {
  final int? categoryId;
  final bool? topFeatured;
  final String type;
  final String? title;
  final bool manor;

  const _TasneefPlacesListViewBody({
    this.categoryId,
    this.topFeatured,
    required this.type,
    this.title,
    required this.manor,
  });

  @override
  State<_TasneefPlacesListViewBody> createState() =>
      _TasneefPlacesListViewBodyState();
}

class _TasneefPlacesListViewBodyState
    extends State<_TasneefPlacesListViewBody> {
  late ScrollController _scrollController;
  bool _isLoadingMore = false;
  List<int> _selectedCategoryIds = []; // for dynamic category tabs

  @override
  void initState() {
    super.initState();
    _scrollController = ScrollController();
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _scrollController.removeListener(_onScroll);
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    // Check if we've reached the bottom of the list
    if (_scrollController.position.pixels >=
            _scrollController.position.maxScrollExtent - 200 &&
        !_isLoadingMore) {
      _loadMoreItems();
    }
  }

  void _loadMoreItems() {
    final cubit = context.read<PlacesCubit>();
    final state = cubit.state;

    if (state is PlacesSuccess && state.hasNextPage && !_isLoadingMore) {
      setState(() {
        _isLoadingMore = true;
      });

      cubit
          .loadMoreItems(
            type: widget.type,
            perPage: 12,
            categoryId: widget.categoryId,
            topFeatured: widget.topFeatured,
            topStores: widget.type == 'stores' ? true : null,
            showNearest: widget.type == 'zads' ? false : null,
          )
          .then((_) {
            setState(() {
              _isLoadingMore = false;
            });
          });
    }
  }

  Future<void> _onRefresh() async {
    final cubit = context.read<PlacesCubit>();
    await cubit.refreshItems(
      type: widget.type,
      perPage: 12,
      categoryId: widget.categoryId,
      topFeatured: widget.topFeatured,
      topStores: widget.type == 'stores' ? true : null,
      showNearest: widget.type == 'zads' ? false : null,
    );
  }

  String _getTitle() {
    if (widget.title != null) return widget.title!;

    if (widget.topFeatured == true) {
      switch (widget.type) {
        case 'stores':
          return "featured_stores".tr();
        case 'zads':
          return "featured_zads".tr();
        case 'events':
          return "featured_events".tr();
        case 'guides':
          return "featured_guides".tr();
        default:
          return "featured_places".tr();
      }
    } else if (widget.categoryId != null) {
      switch (widget.type) {
        case 'stores':
          return "category_stores".tr();
        case 'zads':
          return "category_zads".tr();
        case 'events':
          return "category_events".tr();
        case 'guides':
          return "category_guides".tr();
        default:
          return "category_places".tr();
      }
    }

    // Use category model titles
    final category = dummyCategories.firstWhere(
      (cat) => cat.type == widget.type,
      orElse: () => dummyCategories.first,
    );

    return category.name;
  }

  String _getLoadingText() {
    switch (widget.type) {
      case 'stores':
        return 'stores_loading'.tr();
      case 'zads':
        return 'zads_loading'.tr();
      case 'events':
        return 'events_loading'.tr();
      case 'guides':
        return 'guides_loading'.tr();
      default:
        return 'places_loading'.tr();
    }
  }

  String _getEmptyText() {
    switch (widget.type) {
      case 'stores':
        return 'no_stores'.tr();
      case 'zads':
        return 'no_zads'.tr();
      case 'events':
        return 'no_events'.tr();
      case 'guides':
        return 'no_guides'.tr();
      default:
        return 'no_places'.tr();
    }
  }

  IconData _getEmptyIcon() {
    switch (widget.type) {
      case 'stores':
        return Icons.store_outlined;
      case 'zads':
        return Icons.inventory_outlined;
      case 'guides':
        return Icons.person_outlined;
      default:
        return Icons.location_off_outlined;
    }
  }

  @override
  Widget build(BuildContext context) {
    final FavoriteCubit favoriteCubit = context.read<FavoriteCubit>();

    return GlobalInnerPagesWrapper(
      title: _getTitle(),

      onSearchPressed: () {},
      itemType: widget.type,
      allowTabs: widget.type != 'events',
      onCategorySelected: (categoryIds) {
        setState(() {
          _selectedCategoryIds = categoryIds;
        });
        // Join category IDs with commas for API call
        final categoryIdString = categoryIds.isNotEmpty
            ? categoryIds.join(',')
            : null;
        context.read<PlacesCubit>().refreshItems(
          type: widget.type,
          perPage: 12,
          categories: categoryIdString,
          topFeatured: widget.topFeatured,
          topStores: widget.type == 'stores' ? true : null,
          showNearest: widget.type == 'zads' ? false : null,
        );
      },
      child: BlocListener<FavoriteCubit, FavoriteState>(
        listener: (context, state) {
          if (state is FavoriteSuccess) {
            // Refresh the list when favorite changes
            context.read<PlacesCubit>().refreshItems(
              type: widget.type,
              perPage: 12,
              categoryId: _selectedCategoryIds.isNotEmpty
                  ? _selectedCategoryIds.first
                  : widget.categoryId,
              categories: _selectedCategoryIds.isNotEmpty
                  ? _selectedCategoryIds.join(',')
                  : null,
              topFeatured: widget.topFeatured,
              topStores: widget.type == 'stores' ? true : null,
              showNearest: widget.type == 'zads' ? false : null,
            );
            showCustomSuccessToast(state.message);
          } else if (state is FavoriteError) {
            showCustomFailureToast(state.error);
          }
        },

        child: BlocConsumer<PlacesCubit, PlacesState>(
          listener: (context, state) {
            if (state is PlacesError) {
              showCustomFailureToast(state.message);
            }
          },
          builder: (context, state) {
            if (state is PlacesLoading) {
              return Expanded(
                child: Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const CircularProgressIndicator(),
                      const SizedBox(height: 16),
                      Text(
                        _getLoadingText(),
                        style: const TextStyle(
                          fontSize: 16,
                          color: Colors.grey,
                        ),
                      ),
                    ],
                  ),
                ),
              );
            } else if (state is PlacesSuccess || state is PlacesLoadingMore) {
              final items = state is PlacesSuccess
                  ? state.places
                  : (state as PlacesLoadingMore).places;
              final hasNextPage = state is PlacesSuccess
                  ? state.hasNextPage
                  : true;

              if (items.isEmpty) {
                return Expanded(
                  child: Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          _getEmptyIcon(),
                          size: 64,
                          color: Colors.grey[400],
                        ),
                        const SizedBox(height: 16),
                        Text(
                          _getEmptyText(),
                          style: TextStyle(
                            fontSize: 18,
                            color: Colors.grey[600],
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          "pull_to_refresh".tr(),
                          style: TextStyle(
                            fontSize: 14,
                            color: Colors.grey[500],
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              }

              return Expanded(
                child: RefreshIndicator(
                  onRefresh: _onRefresh,
                  child: ListView.builder(
                    controller: _scrollController,
                    padding: EdgeInsets.zero,
                    itemCount: items.length + (hasNextPage ? 1 : 0),
                    itemBuilder: (context, index) {
                      // Show loading indicator at the bottom when loading more
                      if (index == items.length) {
                        return Container(
                          padding: const EdgeInsets.all(16),
                          alignment: Alignment.center,
                          child: Column(
                            children: [
                              SizedBox(
                                width: 24,
                                height: 24,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2,
                                ),
                              ),
                              SizedBox(height: 8),
                              Text(
                                "loading_more".tr(),
                                style: TextStyle(
                                  fontSize: 12,
                                  color: Colors.grey,
                                ),
                              ),
                            ],
                          ),
                        );
                      }

                      final item = items[index];
                      return TasneefPlacesItemCard(
                        manor: widget.manor,
                        place: item,
                        onTapFavorite: () {
                          shouldExecute(
                            context: context,
                            callback: () {
                              favoriteCubit.addToFavorite(
                                favoriteId: item.id,
                                favoriteType: item.type,
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
                      );
                    },
                  ),
                ),
              );
            } else if (state is PlacesError) {
              return Expanded(
                child: Center(
                  child: Padding(
                    padding: const EdgeInsets.all(24),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          Icons.error_outline,
                          size: 64,
                          color: Colors.red[400],
                        ),
                        const SizedBox(height: 16),
                        Text(
                          "error_label".tr(),
                          style: TextStyle(
                            fontSize: 18,
                            color: Colors.red[600],
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          state.message,
                          style: TextStyle(
                            fontSize: 14,
                            color: Colors.grey[600],
                          ),
                          textAlign: TextAlign.center,
                        ),
                        const SizedBox(height: 24),
                        ElevatedButton.icon(
                          onPressed: () =>
                              context.read<PlacesCubit>().getItemsByType(
                                type: widget.type,
                                perPage: 12,
                                categoryId: _selectedCategoryIds.isNotEmpty
                                    ? _selectedCategoryIds.first
                                    : widget.categoryId,
                                categories: _selectedCategoryIds.isNotEmpty
                                    ? _selectedCategoryIds.join(',')
                                    : null,
                                topFeatured: widget.topFeatured,
                                topStores: widget.type == 'stores'
                                    ? true
                                    : null,
                                showNearest: widget.type == 'zads'
                                    ? false
                                    : null,
                              ),
                          icon: const Icon(Icons.refresh),
                          label: Text("retry".tr()),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Theme.of(context).primaryColor,
                            foregroundColor: Colors.white,
                            padding: const EdgeInsets.symmetric(
                              horizontal: 24,
                              vertical: 12,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              );
            }

            return Expanded(
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(_getEmptyIcon(), size: 64, color: Colors.grey[400]),
                    const SizedBox(height: 16),
                    Text(
                      "no_data".tr(),
                      style: TextStyle(
                        fontSize: 18,
                        color: Colors.grey[600],
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ],
                ),
              ),
            );
          },
        ),
      ),
    );
  }
}
