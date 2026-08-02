import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class MultiSelectorBottomSheet extends StatefulWidget {
  final String title;
  final List<String> allItems;
  final List<String> selectedItems;
  final String Function(String) getLabel;

  const MultiSelectorBottomSheet({
    super.key,
    required this.title,
    required this.allItems,
    required this.selectedItems,
    required this.getLabel,
  });

  @override
  State<MultiSelectorBottomSheet> createState() =>
      _MultiSelectorBottomSheetState();
}

class _MultiSelectorBottomSheetState extends State<MultiSelectorBottomSheet> {
  late List<String> selected;

  @override
  void initState() {
    super.initState();
    selected = [...widget.selectedItems];
  }

  void _toggleSelection(String item) {
    setState(() {
      if (selected.contains(item)) {
        selected.remove(item);
      } else {
        selected.add(item);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Padding(
        padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 16.h),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(widget.title, style: AppTextStyles.font16Bold),
            SizedBox(height: 16.h),
            ListView.separated(
              shrinkWrap: true,
              itemCount: widget.allItems.length,
              separatorBuilder: (_, __) => Divider(color: AppColors.inactive2),
              itemBuilder: (context, index) {
                final item = widget.allItems[index];
                final isSelected = selected.contains(item);

                return ListTile(
                  onTap: () => _toggleSelection(item),
                  title: Text(
                    widget.getLabel(item),
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
            SizedBox(height: 16.h),
            ElevatedButton(
              onPressed: () => Navigator.pop(context, selected),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                padding: EdgeInsets.symmetric(vertical: 14.h),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8.r),
                ),
              ),
              child: Text(
                'تم',
                style: AppTextStyles.font16Bold.copyWith(color: Colors.white),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
