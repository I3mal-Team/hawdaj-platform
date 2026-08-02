import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/features/trip/presentation/manager/fetch_category_cubit/fetch_category_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_wizard/prepare_trip_wizard_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/custom_widgets_head_trip.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/price_option_tile.dart';

class TripCategoriesSelector extends StatelessWidget {
  const TripCategoriesSelector({super.key});

  @override
  Widget build(BuildContext context) {
    final wizardCubit = context.watch<PrepareTripWizardCubit>();
    final selectedCategories = wizardCubit.state.draft.categories;

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16.w),
      child: BlocBuilder<FetchCategoryCubit, FetchCategoryState>(
        builder: (context, state) {
          if (state is FetchCategoryLoading) {
            return const Center(child: CircularProgressIndicator());
          }
          if (state is FetchCategoryError) {
            return Center(
              child: Column(
                children: [
                  Text('${"error_label".tr()}: ${state.message}'),
                  ElevatedButton(
                    onPressed: () =>
                        context.read<FetchCategoryCubit>().fetchCategory(),
                    child: Text("retry".tr()),
                  ),
                ],
              ),
            );
          }
          if (state is FetchCategorySuccess) {
            final categories = state.categoryList;
            final allIds = categories
                .map((c) => c.id ?? -1)
                .where((id) => id > 0)
                .toList();

            final isAllSelected =
                allIds.isNotEmpty &&
                selectedCategories.toSet().containsAll(allIds);

            return Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                CustomWidgetsHeadTrip(
                  title: 'trip_categories_title'.tr(),
                  showButton: true,
                  subtitleText: 'select_places_message'.tr(),
                  buttonText: isAllSelected
                      ? 'unselect_all'.tr()
                      : 'select_all'.tr(),
                  onButtonPressed: () {
                    wizardCubit.setCategories(
                      isAllSelected ? <int>[] : List<int>.from(allIds),
                    );
                  },
                  backgroundColor: isAllSelected
                      ? const Color(0xffF9E7E8)
                      : const Color(0xffF2EBF6),
                  textColor: isAllSelected ? Colors.red : AppColors.primary,
                ),

                HeightSpace(16.h),
                SizedBox(
                  height: 400.h,
                  child: GridView.builder(
                    gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                      crossAxisCount: 2,
                      mainAxisSpacing: 12.h,
                      crossAxisSpacing: 12.w,
                      childAspectRatio: 3.05.w,
                    ),
                    itemCount: categories.length,
                    itemBuilder: (context, index) {
                      final category = categories[index];
                      final id = category.id ?? -1;

                      return PriceOptionTile(
                        isSelected: selectedCategories.contains(id),
                        title: category.name ?? '',
                        onTap: id > 0
                            ? () {
                                final updated = List<int>.from(
                                  selectedCategories,
                                );
                                if (updated.contains(id)) {
                                  updated.remove(id);
                                } else {
                                  updated.add(id);
                                }
                                wizardCubit.setCategories(updated);
                              }
                            : null,
                      );
                    },
                  ),
                ),
              ],
            );
          }
          return const SizedBox();
        },
      ),
    );
  }
}
