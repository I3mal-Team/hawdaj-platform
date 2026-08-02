// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';
import 'package:hawdaj/core/styles/app_colors.dart';

class MultiSelectPopUpWrapper extends StatelessWidget {
  final List<GlobalPopUpData> items;
  final List<int> selectedIds;
  final Function(List<int>) onChanged;
  final Widget child;

  const MultiSelectPopUpWrapper({
    super.key,
    required this.items,
    required this.selectedIds,
    required this.onChanged,
    required this.child,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () async {
        final List<int> tempSelected = List.from(selectedIds);
        final result = await showDialog<List<int>>(
          context: context,
          builder: (_) => AlertDialog(
            backgroundColor: AppColors.white,
            title: Text('choose_items'.tr()),
            content: StatefulBuilder(
              builder: (context, setState) => SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: items.map((item) {
                    final isSelected = tempSelected.contains(item.id);
                    return CheckboxListTile(
                      value: isSelected,
                      title: Text(item.title),
                      onChanged: (bool? value) {
                        setState(() {
                          if (value == true) {
                            tempSelected.add(item.id);
                          } else {
                            tempSelected.remove(item.id);
                          }
                        });
                      },
                    );
                  }).toList(),
                ),
              ),
            ),
            actions: [
              TextButton(
                onPressed: () => Navigator.of(context).pop(),
                child: Text('cancel'.tr()),
              ),
              TextButton(
                onPressed: () => Navigator.of(context).pop(tempSelected),
                child: Text('confirm'.tr()),
              ),
            ],
          ),
        );

        if (result != null) {
          onChanged(result);
        }
      },
      child: child,
    );
  }
}
