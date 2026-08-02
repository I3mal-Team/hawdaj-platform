import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/managers/regions_cubit/regions_cubit.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/generic_selection_bottomsheet.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';

class RegionFilterWidget extends StatelessWidget {
  final Function(RegionModel region) onRegionSelected;
  final TextEditingController controller;

  const RegionFilterWidget({
    super.key,
    required this.onRegionSelected,
    required this.controller,
  });

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<RegionsCubit, RegionsState>(
      builder: (context, state) {
        if (state is RegionsSuccess) {
          return GestureDetector(
            onTap: () {
              GenericSelectionBottomModal.showInlineSelection<RegionModel>(
                context: context,
                items: state.regions,
                title: 'region'.tr(),
                displayText: (region) => region.name,
                onItemSelected: (region) {
                  onRegionSelected(region);
                },
              );
            },
            child: CustomTextField(
              hint: 'region'.tr(),
              enabled: false,
              controller: controller,
            ),
          );
        } else if (state is RegionsLoading) {
          return const Center(child: CircularProgressIndicator());
        } else {
          return const Center(child: Text('خطأ في تحميل المناطق'));
        }
      },
    );
  }
}
