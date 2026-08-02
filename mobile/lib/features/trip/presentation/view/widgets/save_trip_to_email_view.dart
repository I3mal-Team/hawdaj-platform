import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/seconday_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/trip/presentation/manager/save_trip_to_email_cubit/save_trip_to_email_cubit.dart';

class SaveTripToEmailView extends StatelessWidget {
  const SaveTripToEmailView({
    super.key,
    required this.date,
    required this.days,
    required this.endDate,
    required this.itemPerDay,
    required this.items,
    required this.region1,
    required this.region2,
    required this.startDate,
  });

  final String date;
  final String days;
  final String endDate;
  final String itemPerDay;
  final List<List<int>> items;
  final String region1;
  final String region2;
  final String startDate;

  @override
  Widget build(BuildContext context) {
    return Builder(
      builder: (sheetCtx) {
        final c = sheetCtx.read<SaveTripToEmailCubit>();
        return BlocConsumer<SaveTripToEmailCubit, SaveTripToEmailState>(
          listener: (ctx, state) {
            if (state is SaveTripToEmailError) {
              showCustomFailureToast(state.message);
            }
            if (state is SaveTripToEmailSuccess) {
              showCustomSuccessToast("تم الارسال بنجاح");
            }
          },
          builder: (ctx, state) {
            final isLoading = state is SaveTripToEmailLoading;

            return SingleChildScrollView(
              padding: EdgeInsets.only(
                bottom: MediaQuery.of(sheetCtx).viewInsets.bottom,
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "send_email_title".tr(),
                    style: AppTextStyles.font20Bold.copyWith(
                      color: AppColors.obsidianBlack,
                    ),
                  ),

                  HeightSpace(12.h),

                  // الاسم
                  Row(
                    children: [
                      Text(
                        "label_name".tr(),
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
                    controller: c.nameController, // <-- من الكيوبت
                    allowUpperHint: false,
                    hint: "hint_enter_name".tr(),
                    leadingIconPath: AppAssets.sms,
                    height: 44.h,
                    enabled: !isLoading,
                  ),

                  HeightSpace(16.h),

                  // عنوان الرحلة
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
                    controller: c.tripNameController, // <-- من الكيوبت
                    allowUpperHint: false,
                    hint: "hint_enter_trip_title".tr(),
                    leadingIconPath: AppAssets.sms,
                    height: 44.h,
                    enabled: !isLoading,
                  ),

                  HeightSpace(16.h),

                  // البريد الإلكتروني
                  Row(
                    children: [
                      Text(
                        "label_email".tr(),
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
                    controller: c.emailController, // <-- من الكيوبت
                    allowUpperHint: false,
                    hint: 'example@mail.com',
                    leadingIconPath: AppAssets.sms,
                    height: 44.h,
                    inputType: TextInputType.emailAddress,
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
                                  // 1) فاليديشن سريعة من الكيوبت
                                  final error = c.validateInputs();
                                  if (error != null) {
                                    showCustomFailureToast(error);
                                    return;
                                  }

                                  FocusScope.of(sheetCtx).unfocus();

                                  c.submit(
                                    date: date,
                                    days: days,
                                    endDate: endDate,
                                    itemPerDay: itemPerDay,

                                    items: items,
                                    region1: region1.toString(),
                                    region2: region2.toString(),
                                    startDate: startDate,
                                  );
                                },
                        ),
                      ),
                      WidthSpace(8.w),
                      Expanded(
                        child: SecondaryButton(
                          title: 'cancel'.tr(),

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
