import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/features/home/data/model/explore_category_model.dart';

class LandmarkTypeBottomSheet extends StatefulWidget {
  final Function(ExploreCategoryModel) onCategorySelected;
  final ExploreCategoryModel? initialSelection;

  const LandmarkTypeBottomSheet({
    Key? key,
    required this.onCategorySelected,
    this.initialSelection,
  }) : super(key: key);

  @override
  State<LandmarkTypeBottomSheet> createState() =>
      _LandmarkTypeBottomSheetState();
}

class _LandmarkTypeBottomSheetState extends State<LandmarkTypeBottomSheet> {
  ExploreCategoryModel? selectedCategory;

  @override
  void initState() {
    super.initState();
    selectedCategory = widget.initialSelection;
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Title and subtitle
        Text(
          "points_card_add_landmark".tr(),
          style: AppTextStyles.font18Medium.copyWith(
            color: AppColors.obsidianBlack,
          ),
          textAlign: TextAlign.center,
        ),
        SizedBox(height: 8.h),
        Text(
          'select_landmark_type'.tr(),
          style: AppTextStyles.font14Regular.copyWith(
            color: AppColors.mainGrey,
          ),
          textAlign: TextAlign.center,
        ),
        SizedBox(height: 24.h),

        // Categories grid
        GridView.builder(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisCount: 2,
            crossAxisSpacing: 8.w,
            mainAxisSpacing: 8.h,
            childAspectRatio: 3,
          ),
          itemCount: dummyLandMarks.length,
          itemBuilder: (context, index) {
            final category = dummyLandMarks[index];
            final isSelected = selectedCategory?.id == category.id;

            return GestureDetector(
              onTap: () {
                setState(() {
                  selectedCategory = category;
                });
              },
              child: Container(
                decoration: BoxDecoration(
                  border: Border.all(
                    color: isSelected ? AppColors.primary : AppColors.lightGrey,
                    width: 1.5,
                  ),
                  borderRadius: BorderRadius.circular(12.r),
                  gradient: isSelected
                      ? LinearGradient(
                          begin: Alignment(0.00, 0.50),
                          end: Alignment(1.00, 0.50),
                          colors: [Colors.white, const Color(0x84F2EBF6)],
                        )
                      : null,
                  // color: isSelected
                  //     ? AppColors.primary.withOpacity(0.1)
                  //     : AppColors.white,
                ),
                child: Row(
                  children: [
                    SizedBox(width: 12.w),
                    // Radio button
                    Container(
                      width: 20.w,
                      height: 20.h,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        border: Border.all(
                          color: isSelected
                              ? AppColors.primary
                              : AppColors.mainGrey,
                          width: 2,
                        ),
                        color: isSelected ? AppColors.white : AppColors.white,
                      ),
                      child: isSelected
                          ? Container(
                              width: 5.w,
                              height: 5.h,

                              decoration: BoxDecoration(
                                shape: BoxShape.circle,
                                border: Border.all(
                                  color: AppColors.white,
                                  width: 2,
                                ),
                                color: isSelected
                                    ? AppColors.primary
                                    : AppColors.white,
                              ),
                              child: SizedBox(),
                            )
                          : null,
                    ),
                    SizedBox(width: 12.w),
                    // Category name
                    Expanded(
                      child: Text(
                        category.name.tr(context: context),
                        style: AppTextStyles.font14Medium.copyWith(
                          color: isSelected
                              ? AppColors.primary
                              : AppColors.obsidianBlack,
                        ),
                        textAlign: TextAlign.start,
                      ),
                    ),
                  ],
                ),
              ),
            );
          },
        ),

        SizedBox(height: 32.h),

        // Action buttons
        Row(
          spacing: 16.w,
          children: [
            // Cancel button

            // Add button
            Expanded(
              child: ElevatedButton(
                onPressed: selectedCategory != null
                    ? () {
                        widget.onCategorySelected(selectedCategory!);
                        Navigator.pop(context);
                      }
                    : null,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12.r),
                  ),
                  padding: EdgeInsets.symmetric(vertical: 16.h),
                ),
                child: Text(
                  'add_button'.tr(),
                  style: AppTextStyles.font14Medium.copyWith(
                    color: AppColors.white,
                  ),
                ),
              ),
            ),

            Expanded(
              child: OutlinedButton(
                onPressed: () => Navigator.pop(context),
                style: OutlinedButton.styleFrom(
                  side: BorderSide(color: AppColors.lightGrey),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12.r),
                  ),
                  padding: EdgeInsets.symmetric(vertical: 16.h),
                ),
                child: Text(
                  'close'.tr(),
                  style: AppTextStyles.font14Medium.copyWith(
                    color: AppColors.obsidianBlack,
                  ),
                ),
              ),
            ),
          ],
        ),
      ],
    );
  }
}

// Helper function to show the bottom sheet
Future<ExploreCategoryModel?> showLandmarkTypeBottomSheet({
  required BuildContext context,
  ExploreCategoryModel? initialSelection,
}) async {
  ExploreCategoryModel? selectedCategory;

  await baseBottomSheet(
    context: context,
    hideNavBar: true,
    title: null, // We handle the title inside the widget

    child: LandmarkTypeBottomSheet(
      initialSelection: initialSelection,
      onCategorySelected: (category) {
        selectedCategory = category;
      },
    ),
  );

  return selectedCategory;
}
