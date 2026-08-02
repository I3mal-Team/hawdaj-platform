import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_wizard/prepare_trip_wizard_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/price_option_tile.dart';

class TransportationOptionsWidget extends StatelessWidget {
  const TransportationOptionsWidget({super.key});

  @override
  Widget build(BuildContext context) {
    final draft = context.select((PrepareTripWizardCubit c) => c.state.draft);
    final vehicleType = draft.vehicleType; // 'car' | 'plane' | ...

    final transportOptions = [
      {
        'id': 'car',
        'title': 'transport_car_title',
        'subtitle': 'transport_car_sub',
      },
      {
        'id': 'plane',
        'title': 'transport_plane_title',
        'subtitle': 'transport_plane_sub',
      },
    ];

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(12),
      decoration: ShapeDecoration(
        color: const Color(0xFFF8FAFC),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'select_transport_message'.tr(),
            style: AppTextStyles.font16Regular.copyWith(color: AppColors.black),
          ),
          HeightSpace(12.h),

          SizedBox(
            height: 120.h, // حدد الارتفاع حسب تصميمك
            child: GridView.builder(
              physics: const NeverScrollableScrollPhysics(),
              gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                mainAxisSpacing: 12.h,
                crossAxisSpacing: 12.w,
                childAspectRatio: 2,
              ),
              itemCount: transportOptions.length,
              itemBuilder: (context, index) {
                final option = transportOptions[index];
                return PriceOptionTile(
                  isSelected: vehicleType == option['id'],
                  title: option['title']!.tr(),
                  subtitle: option['subtitle']!.tr(),
                  onTap: () => context
                      .read<PrepareTripWizardCubit>()
                      .setVehicleType(option['id']!),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}
