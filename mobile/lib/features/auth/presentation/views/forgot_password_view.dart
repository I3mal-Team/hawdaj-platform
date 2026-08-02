import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/forgot_password_cubit/forgot_password_cubit.dart';
import 'package:hawdaj/features/auth/presentation/views/auth_pages_wrapper.dart';

class ForgotPasswordView extends StatefulWidget {
  const ForgotPasswordView({super.key});

  @override
  State<ForgotPasswordView> createState() => _ForgotPasswordViewState();
}

class _ForgotPasswordViewState extends State<ForgotPasswordView> {
  final TextEditingController emailController = TextEditingController();

  @override
  void dispose() {
    emailController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<ForgotPasswordCubit, ForgotPasswordState>(
      listener: (context, state) {
        if (state is ForgotPasswordSuccess) {
          showCustomSuccessToast(state.message);

          // push(
          //   '${RoutesKeys.kOtpVerificationView}?email=${emailController.text}',
          //   context,
          // );
        } else if (state is ForgotPasswordError) {
          showCustomFailureToast(state.message);
        }
      },
      builder: (context, state) {
        final isLoading = state is ForgotPasswordLoading;

        return AuthPagesWrapper(
          children: [
            Row(
              children: [
                Text(
                  "forgot_password_title".tr(),
                  textAlign: TextAlign.right,
                  style: TextStyle(
                    color: Colors.black,
                    fontSize: 20.sp,
                    fontFamily: AppFonts.theYearOfTheCamel,
                    fontWeight: FontWeight.w900,
                  ),
                ),
              ],
            ),
            HeightSpace(8.h),

            Row(
              crossAxisAlignment: CrossAxisAlignment.start,

              children: [
                Expanded(
                  child: Text(
                    "forgot_password_description".tr(),
                    //  textAlign: TextAlign.right,
                    style: TextStyle(
                      color: const Color(0xFF4B5565),
                      fontSize: 14.sp,
                      fontFamily: AppFonts.brandoArabic,
                      fontWeight: FontWeight.w400,
                    ),
                  ),
                ),
              ],
            ),
            HeightSpace(24.h),

            Row(
              children: [
                Text(
                  "email".tr(),
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
              controller: emailController,
              allowUpperHint: false,
              hint: "email".tr(),
              leadingIconPath: AppAssets.sms,
              height: 44.h,
            ),
            HeightSpace(24.h),

            PrimaryButton(
              title: isLoading ? "sending".tr() : "send_verification_code".tr(),
              onTap: isLoading
                  ? null
                  : () {
                      final email = emailController.text.trim();
                      if (email.isEmpty) {
                        showCustomFailureToast("enter_email".tr());

                        return;
                      }
                      context.read<ForgotPasswordCubit>().forgotPassword(email);
                    },
            ),
            HeightSpace(16.h),

            // Back to login
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                GestureDetector(
                  onTap: () {
                    context.pop();
                  },
                  child: Text(
                    "back_to_login".tr(),
                    style: TextStyle(
                      color: const Color(0xFF6A4690),
                      fontSize: 14.sp,
                      fontFamily: AppFonts.brandoArabic,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ),
              ],
            ),
          ],
        );
      },
    );
  }
}
