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
import 'package:hawdaj/core/routing/route_utils.dart' show pushReplacement;
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/prepare_trip_data.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/trip_model.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_cubit/save_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_save_trip_done.dart';

class SaveTripView extends StatelessWidget {
  const SaveTripView({
    super.key,
    required this.tripModel,
    required this.model,
    this.itemsOverride, // 👈 جديد
  });

  final TripModel tripModel;
  final TripData model;

  final List<List<int>>? itemsOverride; // 👈 جديد

  @override
  Widget build(BuildContext context) {
    return Builder(
      builder: (sheetCtx) {
        final c = sheetCtx.read<SaveTripCubit>();

        return BlocConsumer<SaveTripCubit, SaveTripState>(
          listener: (ctx, state) {
            if (state is SaveTripError) {
              showCustomFailureToast(state.message);
              Navigator.of(sheetCtx).pop();
            }
            if (state is SaveTripSuccess) {
              showCustomSuccessToast('save_trip_success'.tr());
              Navigator.of(context).pop();

              Future.microtask(() {
                final ctx2 = Navigator.of(context).overlay!.context;
                baseBottomSheet(
                  context: ctx2,
                  hideNavBar: false,
                  child: BottomSheetTripSaveTripDone(
                    title: "save_trip_done_title".tr(),
                    onTap: () {
                      pushReplacement(RoutesKeys.kMyTripView, ctx2);
                    },
                  ),
                );
              });
            }
          },
          builder: (ctx, state) {
            final isLoading = state is SaveTripLoading;

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
                                  final error = c.validateInputs();
                                  if (error != null) {
                                    showCustomFailureToast(error);
                                    return;
                                  }

                                  FocusScope.of(sheetCtx).unfocus();

                                  // 👇 استخدم itemsOverride لو موجودة، وإلا ارجع لـ tripModel.items، وإلا ابعت ليست فاضية
                                  final itemsToSend =
                                      itemsOverride ??
                                      (tripModel.items ?? <List<int>>[]);

                                  c.submit(
                                    date: tripModel.date ?? '',
                                    days: tripModel.days.toString(),
                                    endDate: tripModel.endDate ?? '',
                                    itemPerDay: tripModel.funnyPlacePerDay
                                        .toString(),
                                    items: itemsToSend, // ✅ أهم سطر
                                    region1: tripModel.region1 ?? '',
                                    region2: tripModel.region2 ?? '',
                                    startDate: tripModel.startDate ?? '',
                                    userId: model.userId,
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
