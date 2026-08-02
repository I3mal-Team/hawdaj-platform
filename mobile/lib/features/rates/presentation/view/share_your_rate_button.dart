import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/rates/presentation/manager/rate_cubit/rate_cubit.dart';

class ShareYourRateButton extends StatelessWidget {
  const ShareYourRateButton({
    super.key,
    required this.type,
    required this.parentId,
    this.width,
  });
  final String type;
  final String parentId;
  final double? width;
  @override
  Widget build(BuildContext context) {
    return Align(
      alignment: Alignment.center,
      child: PrimaryButton(
        title: 'share_button'.tr(),
        iconPath: AppAssets.svgMessage,
        width: width ?? 200.w,
        onTap: () async {
          final cubit = context.read<RateCubit>();
          await cubit.init();
          await baseBottomSheet(
            context: context,
            title: '',
            hideNavBar: true,
            child: _RateSheet(type: type, parentId: parentId),
          );
        },
      ),
    );
  }
}

class _RateSheet extends StatelessWidget {
  const _RateSheet({required this.type, required this.parentId});
  final String type;
  final String parentId;

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<RateCubit>();
    final nameCtrl = cubit.nameController;
    final emailCtrl = cubit.emailController;
    final commentCtrl = cubit.rateTextController;
    final isUserLoggedIn = context.select<RateCubit, bool>((c) => c.hasUser);

    return BlocConsumer<RateCubit, RateState>(
      listener: (context, state) {
        if (state is RateError) {
          showCustomFailureToast(state.message);
        }
        if (state is RateAdded) {
          showCustomSuccessToast('add_success'.tr());
          cubit.reset(); // إعادة تعيين الحقول
          Navigator.pop(context);
        }
      },
      builder: (context, state) {
        final isLoading = state is RateLoading;

        return Padding(
          padding: EdgeInsets.only(
            left: 16.w,
            right: 16.w,
            bottom: MediaQuery.of(context).viewInsets.bottom + 16.h,
            top: 8.h,
          ),
          child: SingleChildScrollView(
            child: AbsorbPointer(
              absorbing: isLoading,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  HeightSpace(8.h),
                  Image.asset(AppAssets.imagOk, width: 80.w, height: 80.h),
                  HeightSpace(16.h),
                  Text(
                    "sheet_title".tr(),
                    textAlign: TextAlign.center,
                    style: AppTextStyles.font16Medium.copyWith(
                      color: AppColors.obsidianBlack,
                    ),
                  ),
                  HeightSpace(8.h),
                  Text(
                    "sheet_subtitle".tr(),
                    textAlign: TextAlign.center,
                    style: AppTextStyles.font14Regular.copyWith(
                      color: AppColors.grey,
                    ),
                  ),
                  HeightSpace(24.h),

                  RatingBar.builder(
                    initialRating: cubit.rating,
                    minRating: 1,
                    allowHalfRating: false,
                    itemCount: 5,
                    itemPadding: const EdgeInsets.symmetric(horizontal: 6),
                    itemBuilder: (context, _) =>
                        SvgPicture.asset(AppAssets.star),
                    onRatingUpdate: cubit.setRating,
                  ),

                  HeightSpace(20.h),

                  if (!isUserLoggedIn) ...[
                    CustomTextField(
                      hint: "name_hint".tr(),
                      controller: nameCtrl,
                    ),
                    HeightSpace(16.h),
                    CustomTextField(
                      hint: "email_hint".tr(),
                      controller: emailCtrl,
                      inputType: TextInputType.emailAddress,
                    ),
                    HeightSpace(16.h),
                  ],
                  HeightSpace(16.h),
                  CustomTextField(
                    hint: "comment_hint".tr(),
                    maxLines: 6,
                    controller: commentCtrl,
                  ),

                  HeightSpace(20.h),

                  Row(
                    children: [
                      Expanded(
                        child: PrimaryButton(
                          title: isLoading ? 'loading'.tr() : "share".tr(),
                          onTap: () async {
                            await cubit.submit(
                              type: type,
                              parentId: '$parentId',
                            );
                          },
                          active: !isLoading,
                        ),
                      ),
                      WidthSpace(16.w),
                      Expanded(
                        child: PrimaryButton(
                          title: 'cancel'.tr(),
                          backgroundColor: const Color(0xFFF5F7F8),
                          textColor: AppColors.obsidianBlack,
                          onTap: () {
                            FocusScope.of(context).unfocus();
                            Navigator.pop(context);
                            cubit.reset(); // إعادة تعيين الحقول
                          },
                          active: !isLoading,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }
}
