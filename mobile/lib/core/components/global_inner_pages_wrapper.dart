import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/auth/presentation/views/register_view.dart';
import 'package:hawdaj/features/tasneef/presentation/views/tasneef_search_filter_widget.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/back_button.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tab_item.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:get_it/get_it.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/categories/categories_cubit.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/categories/categories_state.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'dart:ui' as ui;

class GlobalInnerPagesWrapper extends StatelessWidget {
  final String title;
  final VoidCallback? onFavPressed;
  final VoidCallback? onSearchPressed;
  final bool allowTabs;
  final Widget child;
  final String?
  itemType; // to load categories dynamically (places, stores, zads, stories, events, apps) except guides
  final Function(List<int> categoryIds)?
  onCategorySelected; // multi-select categories
  const GlobalInnerPagesWrapper({
    super.key,
    required this.title,
    this.onFavPressed,
    this.onSearchPressed,
    this.allowTabs = true,
    required this.child,
    this.itemType,
    this.onCategorySelected,
  });

  @override
  Widget build(BuildContext context) {
    final topPadding = MediaQuery.of(context).padding.top;

    return Scaffold(
      body: SizedBox(
        width: screenWidth,
        child: Stack(
          children: [
            Container(
              height: 166.h,
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                  colors: [
                    Color(0xFFA47798),
                    Color(0xFFC4A7B8),
                    Color(0xFFFFFFFF),
                  ],
                ),
              ),
            ),
            SizedBox(
              width: screenWidth,
              child: Padding(
                padding: EdgeInsets.symmetric(horizontal: 16.h),
                child: Column(
                  children: [
                    HeightSpace(8.h + topPadding),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        CustomBackButton(),
                        Text(
                          title,
                          textAlign: TextAlign.right,
                          style: TextStyle(
                            color: Colors.black /* Color-Neutrals-Black */,
                            fontSize: 20.sp,
                            fontFamily: AppFonts.theYearOfTheCamel,
                            fontWeight: FontWeight.w900,
                          ),
                        ),
                        if (onFavPressed != null)
                          GestureDetector(
                            onTap: onFavPressed,
                            child: Container(
                              width: 40.w,
                              height: 40.h,
                              decoration: BoxDecoration(
                                borderRadius: BorderRadius.circular(1000),
                                color: Color(0xffF2EBF6).withOpacity(.64),
                              ),
                              child: Icon(
                                Icons.favorite,
                                color: Colors.red,
                                size: 20.h,
                              ),
                            ),
                          )
                        else
                          SizedBox(width: 40.w, height: 40.h),
                      ],
                    ),
                    HeightSpace(8.h),
                    if (onSearchPressed != null)
                      GestureDetector(
                        onTap: onSearchPressed,
                        child: Container(
                          height: 50.h,
                          padding: EdgeInsets.symmetric(horizontal: 20.w),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(50.r),
                            border: Border.all(
                              width: 1,
                              color: Color(0xffEEF2F6),
                            ),
                          ),
                          child: Directionality(
                            textDirection: ui.TextDirection.ltr,
                            child: Row(
                              children: [
                                SvgPicture.asset(AppAssets.searchNormal),
                                SizedBox(width: 10.w),
                                Expanded(
                                  child: TasneefSearchFilterWidget(
                                    itemType: itemType ?? 'places',
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ),
                    HeightSpace(16.h),
                    // if (allowTabs)
                    //   Container(
                    //     width: screenWidth,
                    //     padding: EdgeInsets.all(8.r),
                    //     decoration: BoxDecoration(
                    //       color: Color(0xffF8FAFC),
                    //       borderRadius: BorderRadius.circular(8.r),
                    //     ),
                    //     child: SingleChildScrollView(
                    //       scrollDirection: Axis.horizontal,
                    //       child: Row(
                    //         children: [
                    //           TabItem(active: true, title: 'الكل'),
                    //           TabItem(title: 'المفضلة'),
                    //           TabItem(title: 'الأحدث'),
                    //           TabItem(title: 'الأكثر مشاهدة'),
                    //           TabItem(title: 'الأكثر تقييماً'),
                    //         ],
                    //       ),
                    //     ),
                    //   ),
                    // // Dynamic categories tabs
                    if (itemType != null &&
                        onCategorySelected != null &&
                        allowTabs)
                      _DynamicCategoriesTabs(
                        itemType: itemType!,
                        onCategorySelected: onCategorySelected,
                      ),
                    HeightSpace(16.h),
                    child,
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _DynamicCategoriesTabs extends StatefulWidget {
  final String itemType;
  final Function(List<int> categoryIds)? onCategorySelected;

  const _DynamicCategoriesTabs({
    required this.itemType,
    this.onCategorySelected,
  });

  @override
  State<_DynamicCategoriesTabs> createState() => _DynamicCategoriesTabsState();
}

class _DynamicCategoriesTabsState extends State<_DynamicCategoriesTabs> {
  final Set<int> selectedCategoryIds = {};

  @override
  Widget build(BuildContext context) {
    // Don't show category tabs for stories (swalef) type
    if (widget.itemType == 'stories') {
      return const SizedBox.shrink();
    }

    return BlocProvider(
      create: (context) =>
          GetIt.instance<CategoriesCubit>()
            ..loadMainCategories(widget.itemType),
      child: BlocBuilder<CategoriesCubit, CategoriesCState>(
        builder: (context, state) {
          if (state is CategoriesLoading) {
            return const SizedBox.shrink(); // Don't show loading state
          }

          if (state is CategoriesCSuccess && state.categories.isNotEmpty) {
            final categories = state.categories;
            final allTabsItems = <Widget>[];

            // Add "الكل" (All) tab first
            allTabsItems.add(
              TabItem(
                title: 'filter_all'.tr(),
                active: selectedCategoryIds.isEmpty,
                onTap: () {
                  setState(() {
                    selectedCategoryIds.clear();
                  });
                  widget.onCategorySelected?.call([]);
                },
              ),
            );

            // Add category tabs
            for (int index = 0; index < categories.length; index++) {
              final category = categories[index];
              final isSelected = selectedCategoryIds.contains(category.id);

              allTabsItems.add(
                TabItem(
                  title: category.name ?? 'Unknown',
                  active: isSelected,
                  onTap: () {
                    setState(() {
                      if (isSelected) {
                        selectedCategoryIds.remove(category.id);
                      } else {
                        selectedCategoryIds.add(category.id);
                      }
                    });

                    widget.onCategorySelected?.call(
                      selectedCategoryIds.toList(),
                    );
                  },
                ),
              );
            }

            return Container(
              padding: const EdgeInsets.all(6),
              decoration: ShapeDecoration(
                color: const Color(0xFFF8FAFC),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
              height: 50.h,
              child: ListView.separated(
                scrollDirection: Axis.horizontal,
                itemCount: allTabsItems.length,
                separatorBuilder: (context, index) => const SizedBox(width: 8),
                itemBuilder: (context, index) => allTabsItems[index],
              ),
            );
          }

          // Don't show anything if no categories (including error state)
          return const SizedBox.shrink();
        },
      ),
    );
  }
}
