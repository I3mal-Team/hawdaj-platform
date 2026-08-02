import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart'
    show go, push, pushReplacement;
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_v2_cubit/save_trip_v2_cubit.dart';

import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_save_trip_done.dart';

class NewSaveTripV2ViewBaseSheet extends StatelessWidget {
  const NewSaveTripV2ViewBaseSheet({
    super.key,
    required this.tripToken,
    this.itemsOverride,
  });

  final String tripToken;
  final List<List<int>>? itemsOverride;

  @override
  Widget build(BuildContext context) {
    return Builder(
      builder: (sheetCtx) {
        final c = sheetCtx.read<SaveTripV2Cubit>();

        return BlocConsumer<SaveTripV2Cubit, SaveTripV2State>(
          listener: (ctx, state) {
            if (state is SaveTripV2Failure) {
              showCustomFailureToast(state.error);
              Navigator.of(sheetCtx).pop();
            }

            if (state is SaveTripV2Success) {
              showCustomSuccessToast('save_trip_success'.tr());

              // ✅ احفظ Context آمن قبل ما تقفل الـ BottomSheet
              final safeContext = Navigator.of(context).overlay!.context;

              // بعدين اقفل الـ BottomSheet
              Navigator.of(context).pop();

              Future.microtask(() {
                baseBottomSheet(
                  context: safeContext,
                  hideNavBar: false,
                  child: BottomSheetTripSaveTripDone(
                    title: "save_trip_done_title".tr(),
                    onTap: () async {
                      // ✅ استخدم الـ safeContext في كل عمليات التنقل
                      go(RoutesKeys.kHomeView, safeContext);

                      await Future.delayed(const Duration(milliseconds: 300));

                      push(RoutesKeys.kNewMyTripView, safeContext);
                    },
                  ),
                );
              });
            }
          },

          builder: (ctx, state) {
            final isLoading = state is SaveTripV2Loading;

            return SingleChildScrollView(
              padding: EdgeInsets.only(
                bottom: MediaQuery.of(sheetCtx).viewInsets.bottom,
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'save_trip_title'.tr(),
                    style: AppTextStyles.font20Bold.copyWith(
                      color: AppColors.obsidianBlack,
                    ),
                  ),
                  HeightSpace(8.h),
                  Text(
                    "save_trip_subtitle".tr(),
                    style: AppTextStyles.font14Regular.copyWith(
                      color: AppColors.dark60,
                    ),
                  ),
                  HeightSpace(16.h),
                  Row(
                    children: [
                      Text(
                        "label_trip_title".tr(),
                        style: TextStyle(
                          color: Colors.black,
                          fontSize: 14.sp,
                          fontFamily: AppFonts.brandoArabic,
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                    ],
                  ),
                  HeightSpace(8.h),
                  CustomTextField(
                    controller: c.tripNameController,
                    allowUpperHint: false,
                    hint: "hint_enter_trip_title".tr(),
                    leadingIconPath: AppAssets.routing,
                    height: 44.h,
                    enabled: !isLoading,
                  ),
                  HeightSpace(20.h),
                  Row(
                    children: [
                      Expanded(
                        child: PrimaryButton(
                          title: isLoading ? 'sending'.tr() : 'send'.tr(),
                          onTap: isLoading
                              ? null
                              : () {
                                  final error = c.validateInputs.call();
                                  if (error != null) {
                                    showCustomFailureToast(error);
                                    return;
                                  }

                                  FocusScope.of(sheetCtx).unfocus();
                                  final itemsToSend = itemsOverride ?? [];

                                  c.saveTripV2(
                                    token: tripToken,
                                    items: itemsToSend,
                                  );
                                },
                        ),
                      ),
                      WidthSpace(8.w),
                      Expanded(
                        child: PrimaryButton(
                          title: 'cancel'.tr(),
                          backgroundColor: AppColors.dark50,
                          textColor: AppColors.obsidianBlack,
                          onTap: isLoading
                              ? null
                              : () => Navigator.of(sheetCtx).pop(),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }
}
