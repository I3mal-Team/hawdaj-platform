import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart' hide BackButton;
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:get_it/get_it.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/global_inner_pages_wrapper.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_tour_guides_item_card.dart';
import '../../domain/repositories/tasneef_repository.dart';
import '../cubits/places_cubit/places_cubit.dart';
import '../cubits/places_cubit/places_state.dart';

class TasneefTourGuidesListView extends StatelessWidget {
  const TasneefTourGuidesListView({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) =>
          PlacesCubit(tasneefRepository: GetIt.instance<TasneefRepository>())
            ..getGuides(
              perPage: 12,
              topRated: true, // Default to top rated guides
            ),
      child: const _TasneefTourGuidesListViewBody(),
    );
  }
}

class _TasneefTourGuidesListViewBody extends StatefulWidget {
  const _TasneefTourGuidesListViewBody();

  @override
  State<_TasneefTourGuidesListViewBody> createState() =>
      _TasneefTourGuidesListViewBodyState();
}

class _TasneefTourGuidesListViewBodyState
    extends State<_TasneefTourGuidesListViewBody> {
  late ScrollController _scrollController;
  bool _isLoadingMore = false;

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

      cubit.loadMoreItems(type: 'guides', perPage: 12, topRated: true).then((
        _,
      ) {
        setState(() {
          _isLoadingMore = false;
        });
      });
    }
  }

  Future<void> _onRefresh() async {
    final cubit = context.read<PlacesCubit>();
    await cubit.refreshItems(type: 'guides', perPage: 12, topRated: true);
  }

  @override
  Widget build(BuildContext context) {
    return GlobalInnerPagesWrapper(
      title: "title_tour_guides".tr(),

      onSearchPressed: () {},
      itemType: 'guides',
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
                    SizedBox(
                      width: 40,
                      height: 40,
                      child: CircularProgressIndicator(
                        valueColor: AlwaysStoppedAnimation<Color>(
                          Theme.of(context).primaryColor,
                        ),
                        strokeWidth: 3,
                      ),
                    ),
                    const SizedBox(height: 20),
                    Text(
                      "loading_title".tr(),
                      style: TextStyle(
                        fontSize: 16,
                        color: Colors.grey[600],
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      "loading_subtitle".tr(),
                      style: TextStyle(fontSize: 14, color: Colors.grey[500]),
                    ),
                  ],
                ),
              ),
            );
          } else if (state is PlacesSuccess || state is PlacesLoadingMore) {
            final guides = state is PlacesSuccess
                ? state.places
                : (state as PlacesLoadingMore).places;
            final hasNextPage = state is PlacesSuccess
                ? state.hasNextPage
                : true;

            if (guides.isEmpty) {
              return Expanded(
                child: Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Container(
                        padding: const EdgeInsets.all(20),
                        decoration: BoxDecoration(
                          color: Colors.grey[100],
                          shape: BoxShape.circle,
                        ),
                        child: Icon(
                          Icons.person_off_outlined,
                          size: 48,
                          color: Colors.grey[400],
                        ),
                      ),
                      const SizedBox(height: 20),
                      Text(
                        "empty_title".tr(),
                        style: TextStyle(
                          fontSize: 18,
                          color: Colors.grey[700],
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        "empty_subtitle".tr(),
                        style: TextStyle(fontSize: 14, color: Colors.grey[500]),
                        textAlign: TextAlign.center,
                      ),
                      const SizedBox(height: 20),
                      ElevatedButton.icon(
                        onPressed: _onRefresh,
                        icon: const Icon(Icons.refresh),
                        label: Text('retry'.tr()),
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
              );
            }

            return Expanded(
              child: RefreshIndicator(
                onRefresh: _onRefresh,
                child: ListView.builder(
                  controller: _scrollController,
                  padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  itemCount: guides.length + (hasNextPage ? 1 : 0),
                  itemBuilder: (context, index) {
                    // Show loading indicator at the bottom when loading more
                    if (index == guides.length) {
                      return Container(
                        padding: const EdgeInsets.all(20),
                        alignment: Alignment.center,
                        child: Column(
                          children: [
                            SizedBox(
                              width: 28,
                              height: 28,
                              child: CircularProgressIndicator(
                                strokeWidth: 2.5,
                                valueColor: AlwaysStoppedAnimation<Color>(
                                  Theme.of(context).primaryColor,
                                ),
                              ),
                            ),
                            const SizedBox(height: 12),
                            Text(
                              "loading_more".tr(),
                              style: TextStyle(
                                fontSize: 13,
                                color: Colors.grey[600],
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                          ],
                        ),
                      );
                    }

                    final guide = guides[index];

                    return GestureDetector(
                      onTap: () {
                        push(
                          RoutesKeys.kTourGuideDetailsView,
                          context,
                          extra: guide.id,
                        );
                      },
                      child: TasneefTourGuidesItemCard(guide: guide),
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
                        "error_title".tr(),
                        style: TextStyle(
                          fontSize: 18,
                          color: Colors.red[600],
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        state.message,
                        style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                        textAlign: TextAlign.center,
                      ),
                      const SizedBox(height: 24),
                      ElevatedButton.icon(
                        onPressed: () => context.read<PlacesCubit>().getGuides(
                          perPage: 12,
                          topRated: true,
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
                  Icon(
                    Icons.person_off_outlined,
                    size: 64,
                    color: Colors.grey[400],
                  ),
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
    );
  }
}
