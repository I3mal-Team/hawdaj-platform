import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/should_execute.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_cubit.dart';
import 'package:hawdaj/features/favorites/presentation/manager/favorite_cubit/favorite_state.dart';

import 'package:hawdaj/features/home/presentation/manager/home_cubit/home_cubit.dart';
import 'package:hawdaj/features/home/presentation/manager/home_cubit/home_state.dart';
import 'package:hawdaj/features/home/presentation/manager/sliders_cubit/sliders_cubit.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/applications_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/custom_app_bar_home.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/daily_suggestions_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/distinctive_places_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/explore_by_category_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/home_sliders_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/howdah_guides_section.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/trip_card_view.dart';
import 'package:shimmer/shimmer.dart';

class HomeViewBody extends StatelessWidget {
  const HomeViewBody({super.key});
  Future<void> _onRefresh(BuildContext context) async {
    await Future.wait([
      context.read<HomeCubit>().fetchHome(),
      context.read<SlidersCubit>().fetchSliders(),
    ]);
  }

  @override
  Widget build(BuildContext context) {
    return BlocListener<FavoriteCubit, FavoriteState>(
      listener: (context, state) {
        if (state is FavoriteSuccess) {
          // Optionally, you can refresh the home data or show a success message
          context.read<HomeCubit>().fetchHome();
        }
      },
      child: BlocBuilder<HomeCubit, HomeState>(
        builder: (context, state) {
          if (state is HomeLoading) {
            return HomeSkeletonLoader();
          } else if (state is HomeError) {
            return Center(child: Text(state.message));
          } else if (state is HomeLoaded) {
            final data = state.homeData;

            return RefreshIndicator(
              onRefresh: () => _onRefresh(context),
              child: CustomScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                slivers: [
                  const CustomAppBarHome(),
                  SliverToBoxAdapter(child: SizedBox(height: 16.h)),
                  const SliverToBoxAdapter(child: HomeSlidersSection()),
                  SliverToBoxAdapter(child: SizedBox(height: 10.h)),
                  SliverToBoxAdapter(child: ExploreByCategorySection()),
                  SliverToBoxAdapter(child: SizedBox(height: 12.h)),
                  SliverToBoxAdapter(
                    child: DailySuggestionsSection(
                      place: data.topVisitedPlaces,
                    ),
                  ),
                  SliverToBoxAdapter(child: SizedBox(height: 12.h)),
                  SliverToBoxAdapter(
                    child: ApplicationsSection(
                      applications:
                          data.applications, // Pass the applications list
                    ),
                  ),
                  SliverToBoxAdapter(child: SizedBox(height: 12.h)),
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: EdgeInsets.symmetric(horizontal: 16.w),
                      child: TripCardWidget(
                        title: "trips_section_title".tr(),
                        description: "trips_section_description".tr(),
                        imagePath: AppAssets.tripCard,
                        buttonText: "trips_card_button".tr(),
                        onButtonPressed: () {
                          push(RoutesKeys.kStartTripView, context);
                        },
                      ),
                    ),
                  ),
                  SliverToBoxAdapter(child: SizedBox(height: 12.h)),
                  SliverToBoxAdapter(
                    child: DistinctivePlacesSection(
                      placeResponse: data.topVisitedPlaces,
                    ),
                  ),
                  SliverToBoxAdapter(child: SizedBox(height: 12.h)),
                  SliverToBoxAdapter(
                    child: HowdahGuidesSection(guideResponse: data.guides),
                  ),
                  SliverToBoxAdapter(child: SizedBox(height: 12.h)),
                ],
              ),
            );
          } else {
            return const SizedBox.shrink();
          }
        },
      ),
    );
  }
}

class HomeSkeletonLoader extends StatelessWidget {
  const HomeSkeletonLoader({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 20.h),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Sliders Shimmer
          _buildSliderShimmer(),
          SizedBox(height: 16.h),

          // Categories Title
          _buildTitleShimmer(width: 120.w),
          SizedBox(height: 16.h),

          // Categories List
          _buildHorizontalListShimmer(itemCount: 4, itemWidth: 80.w),
          SizedBox(height: 24.h),

          // Daily Suggestions Title
          _buildTitleShimmer(width: 150.w),
          SizedBox(height: 16.h),

          // Suggestions List
          ..._buildSuggestionsList(),
          SizedBox(height: 24.h),

          // Applications Title
          _buildTitleShimmer(width: 100.w),
          SizedBox(height: 16.h),

          // Applications List
          _buildApplicationShimmer(),
        ],
      ),
    );
  }

  Widget _buildSliderShimmer() {
    return Shimmer.fromColors(
      baseColor: Colors.grey.shade300,
      highlightColor: Colors.grey.shade100,
      child: Container(
        height: 180.h,
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16.r),
        ),
      ),
    );
  }

  Widget _buildTitleShimmer({required double width}) {
    return Shimmer.fromColors(
      baseColor: Colors.grey.shade300,
      highlightColor: Colors.grey.shade100,
      child: Container(height: 20.h, width: width, color: Colors.white),
    );
  }

  Widget _buildHorizontalListShimmer({
    required int itemCount,
    required double itemWidth,
  }) {
    return SizedBox(
      height: 100.h,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        itemCount: itemCount,
        separatorBuilder: (_, __) => SizedBox(width: 12.w),
        itemBuilder: (_, index) => Shimmer.fromColors(
          baseColor: Colors.grey.shade300,
          highlightColor: Colors.grey.shade100,
          child: Container(
            width: itemWidth,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12.r),
            ),
          ),
        ),
      ),
    );
  }

  List<Widget> _buildSuggestionsList() {
    return List.generate(
      2,
      (index) => Padding(
        padding: EdgeInsets.only(bottom: 16.h),
        child: Shimmer.fromColors(
          baseColor: Colors.grey.shade300,
          highlightColor: Colors.grey.shade100,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                height: 120.h,
                width: double.infinity,
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(12.r),
                ),
              ),
              SizedBox(height: 8.h),
              Container(height: 16.h, width: 200.w, color: Colors.white),
              SizedBox(height: 4.h),
              Container(
                height: 14.h,
                width: double.infinity,
                color: Colors.white,
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildApplicationShimmer() {
    return Shimmer.fromColors(
      baseColor: Colors.grey.shade300,
      highlightColor: Colors.grey.shade100,
      child: Container(
        height: 100.h,
        width: double.infinity,
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12.r),
        ),
        child: Padding(
          padding: EdgeInsets.all(12.w),
          child: Row(
            children: [
              Container(
                width: 60.w,
                height: 60.w,
                decoration: BoxDecoration(
                  color: Colors.grey.shade400,
                  borderRadius: BorderRadius.circular(12.r),
                ),
              ),
              SizedBox(width: 12.w),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      height: 16.h,
                      width: 100.w,
                      color: Colors.grey.shade400,
                    ),
                    SizedBox(height: 8.h),
                    Container(
                      height: 14.h,
                      width: double.infinity,
                      color: Colors.grey.shade400,
                    ),
                    SizedBox(height: 8.h),
                    Container(
                      height: 14.h,
                      width: 200.w,
                      color: Colors.grey.shade400,
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
