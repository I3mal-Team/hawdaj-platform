import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_wizard/prepare_trip_wizard_cubit.dart'
    show PrepareTripWizardCubit;
import 'package:hawdaj/features/trip/presentation/view/widgets/custom_widgets_head_trip.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/map_two.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/region_field.dart';

class RegionSelectionStep extends StatelessWidget {
  const RegionSelectionStep({super.key});

  @override
  Widget build(BuildContext context) {
    final wizard = context.watch<PrepareTripWizardCubit>();
    final draft = wizard.state.draft;

    final startName = wizard.region1Name;
    final endName = wizard.region2Name;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        CustomWidgetsHeadTrip(
          title: 'region_selection_title'.tr(),
          showButton: false,
          subtitleText: 'region_selection_subtitle'.tr(),
        ),
        HeightSpace(24.h),

        // البداية
        Text(
          'start_region_label'.tr(),
          style: AppTextStyles.font14Regular.copyWith(
            color: AppColors.obsidianBlack,
          ),
        ),
        HeightSpace(8.h),
        RegionField(
          title:
              (draft.region1.isEmpty &&
                  (startName == null || startName.isEmpty))
              ? "select_start_region".tr()
              : '${"start".tr()}: ${startName ?? draft.region1}', // اعرض الاسم وإن غاب اعرض الـ id
          onTap: () {
            baseBottomSheet(
              context: context,
              title: "select_start_region".tr(),
              hideNavBar: true,
              child: MapScreen(
                onPick: (c) {
                  // مرّر الـ id + name
                  wizard.setRegion1(c.id, c.name);
                  wizard.setCoords1(lat: c.lat, lng: c.lng);
                  print(
                    " c.id: ${c.id} c.name: ${c.name} c.lat: ${c.lat} c.lng: ${c.lng}",
                  );
                },
              ),
            );
          },
        ),

        HeightSpace(24.h),

        // النهاية
        Text(
          "end_region_label".tr(),
          style: AppTextStyles.font14Regular.copyWith(
            color: AppColors.obsidianBlack,
          ),
        ),
        HeightSpace(8.h),
        RegionField(
          title: (draft.region2.isEmpty && (endName == null || endName.isEmpty))
              ? "end_region_label".tr()
              : '${"end".tr()}: ${endName ?? draft.region2}',
          onTap: () {
            baseBottomSheet(
              context: context,
              title: "select_end_region".tr(),
              hideNavBar: true,
              child: MapScreen(
                onPick: (c) {
                  wizard.setRegion2(c.id, c.name);
                  wizard.setCoords2(lat: c.lat, lng: c.lng);
                  print(
                    " c.id: ${c.id} c.name: ${c.name} c.lat: ${c.lat} c.lng: ${c.lng}",
                  );
                },
              ),
            );
          },
        ),
      ],
    );
  }
}
