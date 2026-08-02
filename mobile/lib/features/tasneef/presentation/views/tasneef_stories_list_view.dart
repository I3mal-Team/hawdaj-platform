import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart' hide BackButton;
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:get_it/get_it.dart';
import 'package:hawdaj/core/components/global_inner_pages_wrapper.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_stories_item_card.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/places_cubit/places_cubit.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/places_cubit/places_state.dart';
import '../../domain/repositories/tasneef_repository.dart';

class TasneefStoriesListView extends StatelessWidget {
  const TasneefStoriesListView({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) =>
          PlacesCubit(tasneefRepository: GetIt.instance<TasneefRepository>())
            ..getStories(),
      child: const _TasneefStoriesListViewBody(),
    );
  }
}

class _TasneefStoriesListViewBody extends StatefulWidget {
  const _TasneefStoriesListViewBody();

  @override
  State<_TasneefStoriesListViewBody> createState() =>
      _TasneefStoriesListViewBodyState();
}

class _TasneefStoriesListViewBodyState
    extends State<_TasneefStoriesListViewBody> {
  List<int> _selectedCategoryIds = [];

  @override
  Widget build(BuildContext context) {
    return GlobalInnerPagesWrapper(
      title: 'stories_title'.tr(),
      onSearchPressed: () {},
      itemType: 'stories',
      onCategorySelected: (categoryIds) {
        setState(() {
          _selectedCategoryIds = categoryIds;
        });
        // Join category IDs with commas for API call
        final categoryIdString = categoryIds.isNotEmpty
            ? categoryIds.join(',')
            : null;
        context.read<PlacesCubit>().refreshItems(
          type: 'stories',
          perPage: 12,
          categories: categoryIdString,
        );
      },
      child: Expanded(
        child: BlocBuilder<PlacesCubit, PlacesState>(
          builder: (context, state) {
            if (state is PlacesLoading) {
              return const Center(child: CircularProgressIndicator());
            }

            if (state is PlacesError) {
              return Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      ' ${"error_label".tr()}: ${state.message}',
                      style: TextStyle(fontSize: 16.sp),
                      textAlign: TextAlign.center,
                    ),
                    SizedBox(height: 16.h),
                    ElevatedButton(
                      onPressed: () {
                        context.read<PlacesCubit>().getStories();
                      },
                      child: Text("retry".tr()),
                    ),
                  ],
                ),
              );
            }

            if (state is PlacesSuccess) {
              final stories = state.places
                  .where((item) => item.isStory)
                  .toList();

              if (stories.isEmpty) {
                return Center(child: Text("no_stories".tr()));
              }

              return GridView.builder(
                gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  childAspectRatio: 0.8,
                  crossAxisSpacing: 12.w,
                  mainAxisSpacing: 12.h,
                ),
                padding: EdgeInsets.zero,
                itemCount: stories.length,
                itemBuilder: (context, index) {
                  return TasneefStoriesItemCard(
                    story: stories[index],
                    goToDetails: () {
                      push(
                        RoutesKeys.kStoriesDetailsView,
                        context,
                        extra: stories[index].slug,
                      );
                    },
                  );
                },
              );
            }

            return Center(child: Text("no_stories".tr()));
          },
        ),
      ),
    );
  }
}
