import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_otp_text_field/flutter_otp_text_field.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/auth/presentation/views/auth_pages_wrapper.dart';

class OtpVerificationView extends StatefulWidget {
  final String? email;
  const OtpVerificationView({super.key, this.email});

  @override
  State<OtpVerificationView> createState() => _OtpVerificationViewState();
}

class _OtpVerificationViewState extends State<OtpVerificationView> {
  String otpCode = '';

  @override
  Widget build(BuildContext context) {
    return AuthPagesWrapper(
      children: [
        Row(
          children: [
            Text(
              "otp_title".tr(),
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
          children: [
            Expanded(
              child: RichText(
                textAlign: TextAlign.right,
                text: TextSpan(
                  text: "otp_instruction".tr(),
                  style: TextStyle(
                    color: const Color(0xFF4B5565),
                    fontSize: 14.sp,
                    fontFamily: AppFonts.brandoArabic,
                    fontWeight: FontWeight.w400,
                  ),
                  children: [
                    TextSpan(
                      text: widget.email ?? '+966368767832',
                      style: TextStyle(
                        color: Colors.black,
                        fontSize: 14.sp,
                        fontFamily: AppFonts.brandoArabic,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    TextSpan(
                      text: ' ',
                      style: TextStyle(
                        color: const Color(0xFF4B5565),
                        fontSize: 14.sp,
                        fontFamily: AppFonts.brandoArabic,
                        fontWeight: FontWeight.w400,
                      ),
                    ),
                    WidgetSpan(
                      child: GestureDetector(
                        onTap: () {
                          // Go back to forgot password to edit email
                          context.pop();
                        },
                        child: Text(
                          '(${"edit".tr()})',
                          style: TextStyle(
                            color: const Color(0xFF6A4690),
                            fontSize: 14.sp,
                            fontFamily: AppFonts.brandoArabic,
                            fontWeight: FontWeight.w500,
                            decoration: TextDecoration.underline,
                            decorationColor: const Color(0xFF6A4690),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
        HeightSpace(32.h),

        // OTP Text Field
        OtpTextField(
          numberOfFields: 6,
          fieldWidth: 48.w,
          borderWidth: 1,
          enabledBorderColor: AppColors.inactive2,
          focusedBorderColor: AppColors.primary,
          borderRadius: BorderRadius.circular(12.r),
          borderColor: AppColors.inactive2,
          showFieldAsBox: true,
          textStyle: TextStyle(
            fontSize: 18.sp,
            fontWeight: FontWeight.w600,
            color: Colors.black,
          ),
          onSubmit: (String verificationCode) {
            setState(() {
              otpCode = verificationCode;
            });
          },
        ),
        HeightSpace(32.h),

        PrimaryButton(
          title: "verify_code".tr(),
          onTap: () {
            // Handle OTP verification
            // TODO: Add your verification logic here
          },
        ),
        HeightSpace(16.h),

        // Resend code
        Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              'didnt_receive_code'.tr(),
              style: TextStyle(
                color: const Color(0xFF4B5565),
                fontSize: 14.sp,
                fontFamily: AppFonts.brandoArabic,
                fontWeight: FontWeight.w400,
              ),
            ),
            GestureDetector(
              onTap: () {
                // Handle resend code
                // TODO: Add resend logic here
              },
              child: Text(
                "resend".tr(),
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
        HeightSpace(16.h),

        // Back to login
        Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            GestureDetector(
              onTap: () {
                // Go back to login (pop twice to skip forgot password)
                context.pop();
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
  }
}
