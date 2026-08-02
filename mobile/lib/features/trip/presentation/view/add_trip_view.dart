import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/trip/presentation/manager/fetch_category_cubit/fetch_category_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/fetch_prices_cubit/fetch_prices_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_cubit/prepare_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_wizard/prepare_trip_wizard_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/custom_widgets_head_trip_details.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/region_selection_step.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/step_indicator.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_categories_selector.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_duration_picker.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';

class AddTripView extends StatefulWidget {
  const AddTripView({super.key});

  @override
  State<AddTripView> createState() => _AddTripViewState();
}

class _AddTripViewState extends State<AddTripView> {
  @override
  void initState() {
    super.initState();
    context.read<FetchPricesCubit>().fetchPrices();
    context.read<FetchCategoryCubit>().fetchCategory();
  }

  @override
  Widget build(BuildContext context) {
    final cubit = context.watch<PrepareTripWizardCubit>();
    final wizard = context.watch<PrepareTripWizardCubit>();
    final isLast = wizard.isLastStep;

    return BlocListener<PrepareTripCubit, PrepareTripState>(
      listener: (context, state) {
        if (state is PrepareTripLoading) {
          baseBottomSheet(
            context: context,
            title: "select_end_region".tr(),
            hideNavBar: true,
            child: Column(
              children: [
                Image.asset(
                  AppAssets.lodingTrip,
                  fit: BoxFit.cover,
                  width: 207.w,
                  height: 169.h,
                ),
                Text(
                  "preparing_special_trip".tr(),
                  style: AppTextStyles.font20Bold.copyWith(
                    color: AppColors.black,
                  ),
                ),
                HeightSpace(12.h),
                Text(
                  "preparing_special_trip".tr(),
                  style: AppTextStyles.font14Regular.copyWith(
                    color: AppColors.black,
                  ),
                ),
                HeightSpace(12.h),
              ],
            ),
          );
        } else if (state is PrepareTripFailure) {
          showCustomFailureToast(state.message);
        } else if (state is PrepareTripSuccess) {
          showCustomSuccessToast("trip_prepared_success".tr());
          print('trip token: ${state.enhanced?.data?.token}');

          print('trip: ${state.trip ?? state.enhanced?.data?.token}');

          pushReplacement(
            RoutesKeys.kFinishTrip,
            context,
            extra: state.trip ?? state.enhanced,
          );
        }
      },
      child: Scaffold(
        body: Column(
          children: [
            TripStartAppBar(
              title: "finish_trip_title".tr(),
              onBack: () {
                Navigator.pop(context);
              },
            ),

            Expanded(
              child: Stack(
                children: [
                  Align(
                    alignment: Alignment.bottomCenter,
                    child: Image.asset(
                      AppAssets.converted,
                      fit: BoxFit.cover,
                      width: double.infinity,
                      height: MediaQuery.of(context).size.height * 0.45,
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 16.0),
                    child: Column(
                      children: [
                        HeightSpace(20.h),
                        StepIndicator(
                          steps: [
                            "trip_duration_title".tr(),
                            'start_end'.tr(),
                            'trip_details_title'.tr(),
                            'trip_categories_title'.tr(),
                          ],
                          currentIndex: cubit.state.index,
                        ),
                        HeightSpace(20.h),
                        Expanded(
                          child: PageView(
                            controller: cubit.pageController,
                            onPageChanged: cubit.goTo,
                            children: const [
                              TripDurationPicker(),
                              RegionSelectionStep(),
                              CustomWidgetsHeadTripDetails(),
                              TripCategoriesSelector(),
                            ],
                          ),
                        ),
                        SizedBox(height: 20.h),
                        Row(
                          children: [
                            Expanded(
                              child: PrimaryButton(
                                title: "previous".tr(),
                                onTap: () => wizard.back(),
                                backgroundColor: const Color(0xffF2EBF6),
                                textColor: AppColors.primary,
                              ),
                            ),
                            SizedBox(width: 16.w),
                            Expanded(
                              child:
                                  BlocBuilder<
                                    PrepareTripCubit,
                                    PrepareTripState
                                  >(
                                    builder: (context, state) {
                                      final loading =
                                          state is PrepareTripLoading && isLast;
                                      return PrimaryButton(
                                        title: isLast
                                            ? (loading
                                                  ? "sending".tr()
                                                  : "done".tr())
                                            : 'next'.tr(),

                                        onTap: loading
                                            ? null
                                            : () => wizard.onPrimaryPressed(
                                                context,
                                                useNew: true,
                                              ),
                                      );
                                    },
                                  ),
                            ),
                          ],
                        ),
                        HeightSpace(20.h),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
