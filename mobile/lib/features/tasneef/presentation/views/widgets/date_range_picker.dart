import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';

class DateRangePicker extends StatelessWidget {
  final Function(DateTimeRange?) onDateRangeSelected;
  final DateTimeRange? range;
  const DateRangePicker({
    super.key,
    required this.onDateRangeSelected,
    this.range,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () async {
        var range = await showDateRangePicker(
          context: context,
          firstDate: DateTime.now().subtract(Duration(days: 1000)),
          lastDate: DateTime.now().add(Duration(days: 1000)),
        );
        onDateRangeSelected(range);
      },
      child: CustomTextField(
        enabled: false,
        hint: "choose_date".tr(),
        controller: TextEditingController(
          text: range == null
              ? "choose_date".tr()
              : '${range!.start.toString().split(' ')[0]}-${range!.end.toString().split(' ')[0]}',
        ),
      ),
    );
  }
}
