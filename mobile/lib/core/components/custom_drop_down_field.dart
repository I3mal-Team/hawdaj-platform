// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_wrapper.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class CustomDropDownField extends StatefulWidget {
  final String? hint;
  final String? leadingIconPath;
  final List<GlobalPopUpData> items;
  final List<int>? selectedIds;

  const CustomDropDownField({
    super.key,
    this.hint,
    this.leadingIconPath,
    required this.items,
    this.selectedIds,
  });

  @override
  State<CustomDropDownField> createState() => _CustomDropDownFieldState();
}

class _CustomDropDownFieldState extends State<CustomDropDownField> {
  late final TextEditingController cont;

  @override
  void initState() {
    super.initState();
    cont = TextEditingController();
    _setSelectedText();
  }

  @override
  void didUpdateWidget(covariant CustomDropDownField oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.selectedIds != widget.selectedIds ||
        oldWidget.items != widget.items) {
      _setSelectedText();
    }
  }

  void _setSelectedText() {
    if (widget.selectedIds == null || widget.selectedIds!.isEmpty) {
      cont.text = '';
      return;
    }
    final selectedTitles = widget.items
        .where((item) => widget.selectedIds!.contains(item.id))
        .map((item) => item.title)
        .toList();

    cont.text = selectedTitles.join(', ');
  }

  @override
  Widget build(BuildContext context) {
    return PopUpWrapper(
      selectedId: null, // تعديل لاحق حسب دعم multi-selection داخل PopUpWrapper
      items: widget.items,
      child: CustomTextField(
        controller: cont,
        enabled: false,
        hint: widget.hint,
        allowUpperHint: false,
        style: TextStyle(color: AppColors.uiBlack),
        leadingIconPath: widget.leadingIconPath,
        trailing: Image.asset(AppAssets.arrowDown, width: 20.w),
      ),
    );
  }

  @override
  void dispose() {
    cont.dispose();
    super.dispose();
  }
}
