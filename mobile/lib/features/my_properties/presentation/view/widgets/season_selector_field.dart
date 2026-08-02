import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/multi_select_drop_down_field.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_form_cubit/add_property_form_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/section_field.dart';

import 'package:flutter_svg/flutter_svg.dart';

class SeasonSelectorField extends StatelessWidget {
  const SeasonSelectorField({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<AddPropertyFormCubit, AddPropertyFormState>(
      builder: (context, state) {
        final selectedSeasons = state.seasons ?? [];

        return SectionField(
          label: "best_seasons_label".tr(),
          child: CustomTextField(
            hint: 'choose_best_seasons'.tr(),
            enabled: false,
            leading: SvgPicture.asset(
              AppAssets.cloudNotif,
              width: 18,
              height: 18,
            ),
            controller: TextEditingController(
              text: selectedSeasons
                  .map((s) => AddPropertyFormCubit.getLocalizedSeason(s))
                  .join(', '),
            ),
            trailing: const Icon(Icons.arrow_drop_down_outlined),
          ),
        );
      },
    );
  }
}

class SeasonSelectorFieldNew extends StatelessWidget {
  const SeasonSelectorFieldNew({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<AddPropertyFormCubit, AddPropertyFormState>(
      builder: (context, state) {
        final cubit = context.read<AddPropertyFormCubit>();
        final selectedSeasons = state.seasons ?? [];

        final items = AddPropertyFormCubit.seasonTypes
            .map(
              (e) => GlobalPopUpData(
                id: e.hashCode,
                title: AddPropertyFormCubit.getLocalizedSeason(e),
              ),
            )
            .toList();

        final selectedIds = selectedSeasons.map((e) => e.hashCode).toList();

        return SectionField(
          label: 'best_seasons_label'.tr(),
          child: MultiSelectDropDownField(
            hint: 'choose_best_seasons'.tr(),
            leadingIconPath: AppAssets.cloudNotif,
            items: items,
            selectedIds: selectedIds,
            onChanged: (selectedHashCodes) {
              final selected = AddPropertyFormCubit.seasonTypes.where((season) {
                return selectedHashCodes.contains(season.hashCode);
              }).toList();

              cubit.updateSeasons(selected);
            },
          ),
        );
      },
    );
  }
}
