// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/multi_select_pop_up_wrapper.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class MultiSelectDropDownField extends StatefulWidget {
  final String? hint;
  final String? leadingIconPath;
  final List<GlobalPopUpData> items;
  final List<int> selectedIds;
  final Function(List<int>) onChanged;

  const MultiSelectDropDownField({
    super.key,
    this.hint,
    this.leadingIconPath,
    required this.items,
    required this.selectedIds,
    required this.onChanged,
  });

  @override
  State<MultiSelectDropDownField> createState() =>
      _MultiSelectDropDownFieldState();
}

class _MultiSelectDropDownFieldState extends State<MultiSelectDropDownField> {
  late final TextEditingController cont;

  @override
  void initState() {
    super.initState();
    cont = TextEditingController();
    _setSelectedText();
  }

  @override
  void didUpdateWidget(covariant MultiSelectDropDownField oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.selectedIds != widget.selectedIds ||
        oldWidget.items != widget.items) {
      _setSelectedText();
    }
  }

  void _setSelectedText() {
    if (widget.selectedIds.isEmpty) {
      cont.text = '';
      return;
    }
    final selectedTitles = widget.items
        .where((item) => widget.selectedIds.contains(item.id))
        .map((item) => item.title)
        .join(', ');
    cont.text = selectedTitles;
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        MultiSelectPopUpWrapper(
          items: widget.items,
          selectedIds: widget.selectedIds,
          onChanged: widget.onChanged,
          child: CustomTextField(
            controller: cont,
            enabled: false,
            hint: widget.hint,
            allowUpperHint: false,
            style: TextStyle(color: AppColors.uiBlack),
            leadingIconPath: widget.leadingIconPath,
            trailing: Image.asset(AppAssets.arrowDown, width: 20.w),
          ),
        ),
        if (widget.selectedIds.isNotEmpty) ...[
          SizedBox(height: 8.h),
          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: widget.selectedIds.map((id) {
              final item = widget.items.firstWhere(
                (element) => element.id == id,
              );
              return InputChip(
                label: Text(item.title),
                onDeleted: () {
                  final updated = List<int>.from(widget.selectedIds)
                    ..remove(id);
                  widget.onChanged(updated);
                },
              );
            }).toList(),
          ),
        ],
      ],
    );
  }

  @override
  void dispose() {
    cont.dispose();
    super.dispose();
  }
}
