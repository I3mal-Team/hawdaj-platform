import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';

import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';

import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/store_guide_cubit/store_guide_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/tour_guide_form_cubit/tour_guide_form_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/add_tour_guide_details_view_body.dart';

import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_save_trip_done.dart';
import 'package:hawdaj/core/components/spaces.dart';

class AddTourGuideDetailsView extends StatelessWidget {
  const AddTourGuideDetailsView({
    super.key,
    required this.isEdit,
    this.guideModel,
  });
  final bool isEdit;
  final GuideModel? guideModel;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: AddTourGuideDetailsViewBody(),

      bottomNavigationBar: Padding(
        padding: const EdgeInsets.all(8.0),
        child: Row(
          children: [
            Expanded(
              child: BlocListener<StoreGuideCubit, StoreGuideState>(
                listener: (context, state) {
                  if (state is StoreGuideSuccess) {
                    showCustomSuccessToast("save_success".tr());
                    //     context.read<TourGuideFormCubit>().clear();

                    baseBottomSheet(
                      context: context,
                      hideNavBar: false,
                      child: BottomSheetTripSaveTripDone(
                        onTap: () {
                          context.pop(); // Close bottom sheet
                          context.pop(true); // Return success result
                          context.read<TourGuideFormCubit>().clear();
                        },
                        textButton: 'next'.tr(),

                        title: "tour_guide_saved_title".tr(),
                      ),
                    );
                  } else if (state is StoreGuideError) {
                    showCustomFailureToast(state.message);
                  }
                },
                child: Builder(
                  builder: (context) {
                    final isLoading =
                        context.watch<StoreGuideCubit>().state
                            is StoreGuideLoading;

                    return PrimaryButton(
                      padding: EdgeInsets.zero,
                      title: isLoading ? 'saving'.tr() : 'save'.tr(),
                      onTap: isLoading
                          ? null
                          : () {
                              final formState = context
                                  .read<TourGuideFormCubit>()
                                  .state;

                              // تحقق بسيط قبل النداء (اختياري)
                              if (formState.name.trim().isEmpty ||
                                  formState.selectedRegionIds.isEmpty ||
                                  formState.selectedLanguageIds.isEmpty) {
                                showCustomFailureToast(
                                  'fill_required_fields'.tr(),
                                );
                                return;
                              }

                              context.read<StoreGuideCubit>().storeFromForm(
                                formState,
                              );
                            },
                      height: 60.h,
                    );
                  },
                ),
              ),
            ),
            WidthSpace(8.w),
            Expanded(
              child: PrimaryButton(
                title: 'cancel'.tr(),
                onTap: () => Navigator.pop(context),
                backgroundColor: AppColors.dark50,
                textColor: Colors.black,
                height: 60.h,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
