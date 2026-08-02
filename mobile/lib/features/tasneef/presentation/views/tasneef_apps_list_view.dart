import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart' hide BackButton;
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:get_it/get_it.dart';
import 'package:hawdaj/core/components/global_inner_pages_wrapper.dart';
import 'package:hawdaj/features/tasneef/domain/repositories/tasneef_repository.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/places_cubit/places_cubit.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/places_cubit/places_state.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_apps_item_card.dart';

class TasneefAppsListView extends StatelessWidget {
  const TasneefAppsListView({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) =>
          PlacesCubit(tasneefRepository: GetIt.instance<TasneefRepository>())
            ..getApps(),
      child: const _TasneefAppsListViewBody(),
    );
  }
}

class _TasneefAppsListViewBody extends StatefulWidget {
  const _TasneefAppsListViewBody();

  @override
  State<_TasneefAppsListViewBody> createState() =>
      _TasneefAppsListViewBodyState();
}

class _TasneefAppsListViewBodyState extends State<_TasneefAppsListViewBody> {
  List<int> _selectedCategoryIds = [];

  @override
  Widget build(BuildContext context) {
    return GlobalInnerPagesWrapper(
      title: 'apps'.tr(),
      onSearchPressed: () {},
      itemType: 'apps',
      onCategorySelected: (categoryIds) {
        setState(() {
          _selectedCategoryIds = categoryIds;
        });
        // Join category IDs with commas for API call
        final categoryIdString = categoryIds.isNotEmpty
            ? categoryIds.join(',')
            : null;
        context.read<PlacesCubit>().refreshItems(
          type: 'apps',
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
                child: Text(
                  state.message,
                  style: TextStyle(color: Colors.red, fontSize: 16.sp),
                ),
              );
            }

            if (state is PlacesSuccess) {
              final apps = state.places;

              if (apps.isEmpty) {
                return Center(
                  child: Text(
                    'no_apps_available'.tr(),
                    style: TextStyle(fontSize: 16.sp, color: Colors.grey),
                  ),
                );
              }

              return GridView.builder(
                gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  childAspectRatio: 0.7,
                  crossAxisSpacing: 12.w,
                  mainAxisSpacing: 12.h,
                ),
                padding: EdgeInsets.zero,
                itemCount: apps.length,
                itemBuilder: (context, index) {
                  final app = apps[index];
                  return TasneefAppsItemCard(app: app);
                },
              );
            }

            return const SizedBox.shrink();
          },
        ),
      ),
    );
  }
}
