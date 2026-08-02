import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/widgets.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/features/tasneef/data/models/filter_model.dart';
import 'package:hawdaj/core/components/spaces.dart';

List<FilterModel> _filterModel(TextEditingController nameController) => [
  FilterModel.sawalefName(nameController),
];

class SwalefFilteringWidget extends StatefulWidget {
  const SwalefFilteringWidget({super.key});

  @override
  State<SwalefFilteringWidget> createState() => _SwalefFilteringWidgetState();
}

class _SwalefFilteringWidgetState extends State<SwalefFilteringWidget> {
  final TextEditingController nameController = TextEditingController();

  @override
  void dispose() {
    nameController.dispose();
    super.dispose();
  }

  List<FilterModel> _buildFilterModels() {
    return _filterModel(nameController);
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 16.h),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          ..._buildFilterModels().map(
            (filter) => Column(children: [filter.widget, HeightSpace(8.h)]),
          ),
          HeightSpace(16.h),
          PrimaryButton(
            title: 'search'.tr(),
            onTap: () {
              var filters = _buildFilterModels();
              Map<String, dynamic> filterParams = {};

              // Collect filter values based on their keys and current state
              for (var filter in filters) {
                switch (filter.key) {
                  case 'search':
                    if (nameController.text.isNotEmpty) {
                      filterParams[filter.key] = nameController.text;
                    }
                    break;
                }
              }

              GoRouter.of(context).pop(filterParams);
            },
          ),
        ],
      ),
    );
  }
}
