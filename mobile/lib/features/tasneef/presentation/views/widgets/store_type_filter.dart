import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/generic_selection_bottomsheet.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';

class StoreTypeFilter extends StatelessWidget {
  final bool? isOnline;
  final Function(bool?) onChanged;
  final bool isAddressType;

  const StoreTypeFilter({
    super.key,
    required this.isOnline,
    required this.onChanged,
    this.isAddressType = false,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        GenericSelectionBottomModal.showInlineSelection<bool>(
          context: context,
          items: [true, false],
          title: 'type'.tr(), // ✅ نفس العنوان
          displayText: (value) => (value ? 'أونلاين' : 'الموقع'), // ✅ نفس القيم
          onItemSelected: (value) {
            onChanged(value);
          },
        );
      },
      child: CustomTextField(
        enabled: false,
        hint: 'type'.tr(), // ✅ نفس الـ hint
        controller: TextEditingController(
          text: isOnline == null ? '' : (isOnline! ? 'أونلاين' : 'الموقع'),
        ),
      ),
    );
  }
}
