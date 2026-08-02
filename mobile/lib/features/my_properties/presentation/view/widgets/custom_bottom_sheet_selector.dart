import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/bottom_sheet_item.dart';

class CustomBottomSheetSelector extends StatelessWidget {
  final String title;
  final List<BottomSheetItem> items;
  final int? selectedId;
  final Function(int) onItemSelected;

  const CustomBottomSheetSelector({
    super.key,
    required this.title,
    required this.items,
    required this.onItemSelected,
    this.selectedId,
  });

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Padding(
        padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 16.h),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(title, style: AppTextStyles.font16Bold),
            SizedBox(height: 16.h),
            ListView.separated(
              shrinkWrap: true,
              itemCount: items.length,
              separatorBuilder: (_, __) => Divider(color: AppColors.inactive2),
              itemBuilder: (context, index) {
                final item = items[index];
                final isSelected = item.id == selectedId;

                return ListTile(
                  onTap: () => Navigator.pop(context, item.id),
                  title: Text(
                    item.label,
                    style: TextStyle(
                      color: isSelected ? AppColors.primary : AppColors.uiBlack,
                      fontWeight: isSelected
                          ? FontWeight.bold
                          : FontWeight.normal,
                    ),
                  ),
                  trailing: isSelected
                      ? Icon(Icons.check, color: AppColors.primary)
                      : null,
                );
              },
            ),
            SizedBox(height: 8.h),
          ],
        ),
      ),
    );
  }
}
