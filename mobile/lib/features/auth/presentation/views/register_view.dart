import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/validated_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/app_router.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/core/utils/validators.dart';
import 'package:hawdaj/core/utils/validation_helpers.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubit/register/register_cubit.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubit/register/register_state.dart';
import 'package:hawdaj/features/auth/presentation/views/auth_pages_wrapper.dart';
import 'package:hawdaj/features/auth/presentation/views/login_view.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/social_auth_cubit/social_auth_cubit.dart';
import 'package:hawdaj/features/auth/utils/social_login_platform.dart';

double get screenWidth => MediaQuery.of(parentKey.currentContext!).size.width;

class RegisterView extends StatefulWidget {
  const RegisterView({super.key});

  @override
  State<RegisterView> createState() => _RegisterViewState();
}

class _RegisterViewState extends State<RegisterView> {
  final _formKey = GlobalKey<FormState>();
  final _firstNameController = TextEditingController();
  final _lastNameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();

  @override
  void dispose() {
    _firstNameController.dispose();
    _lastNameController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _handleRegister() {
    if (_formKey.currentState?.validate() ?? false) {
      // Sanitize inputs before sending
      final sanitizedInputs = ValidationHelpers.sanitizeRegistrationInputs(
        firstName: _firstNameController.text,
        lastName: _lastNameController.text,
        email: _emailController.text,
        password: _passwordController.text,
      );

      context.read<RegisterCubit>().register(
        email: sanitizedInputs['email']!,
        firstName: sanitizedInputs['firstName']!,
        lastName: sanitizedInputs['lastName']!,
        password: sanitizedInputs['password']!,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<RegisterCubit, RegisterState>(
      listener: (context, state) {
        if (state is RegisterSuccess) {
          showCustomSuccessToast(state.authResponse.message);

          // Navigate to home after successful registration
          context.go(RoutesKeys.kHomeView);
        } else if (state is RegisterError) {
          showCustomFailureToast(state.message);
        }
      },
      builder: (context, state) {
        return AuthPagesWrapper(
          children: [
            Form(
              key: _formKey,
              child: Column(
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: [
                      Text(
                        "register_title".tr(),
                        //  textAlign: TextAlign.right,
                        style: TextStyle(
                          color: Colors.black,
                          fontSize: 20.sp,
                          fontFamily: AppFonts.theYearOfTheCamel,
                          fontWeight: FontWeight.w900,
                        ),
                      ),
                    ],
                  ),
                  HeightSpace(24.h),

                  // First Name
                  Row(
                    children: [
                      Text(
                        "first_name".tr(),
                        style: TextStyle(
                          color: Colors.black /* Color-Neutrals-Black */,
                          fontSize: 14.sp,
                          fontFamily: AppFonts.brandoArabic,
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                    ],
                  ),
                  HeightSpace(8.h),

                  ValidatedTextField(
                    allowUpperHint: false,
                    hint: "first_name".tr(),
                    leadingIconPath: AppAssets.profile,
                    height: 44.h,
                    controller: _firstNameController,
                    validator: ValidationHelpers.validateFirstName,
                  ),
                  HeightSpace(16.h),

                  // Last Name
                  Row(
                    children: [
                      Text(
                        "last_name".tr(),
                        style: TextStyle(
                          color: Colors.black /* Color-Neutrals-Black */,
                          fontSize: 14.sp,
                          fontFamily: AppFonts.brandoArabic,
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                    ],
                  ),
                  HeightSpace(8.h),

                  ValidatedTextField(
                    allowUpperHint: false,
                    hint: "last_name".tr(),
                    leadingIconPath: AppAssets.profile,
                    height: 44.h,
                    controller: _lastNameController,
                    validator: ValidationHelpers.validateLastName,
                  ),
                  HeightSpace(16.h),

                  // Email
                  Row(
                    children: [
                      Text(
                        "email".tr(),
                        style: TextStyle(
                          color: Colors.black /* Color-Neutrals-Black */,
                          fontSize: 14.sp,
                          fontFamily: AppFonts.brandoArabic,
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                    ],
                  ),
                  HeightSpace(8.h),

                  ValidatedTextField(
                    allowUpperHint: false,
                    hint: "email".tr(),
                    leadingIconPath: AppAssets.sms,
                    height: 44.h,
                    controller: _emailController,
                    inputType: TextInputType.emailAddress,
                    validator: AppValidators.validateEmail,
                  ),
                  HeightSpace(16.h),

                  // Password
                  Row(
                    children: [
                      Text(
                        "password".tr(),
                        style: TextStyle(
                          color: Colors.black /* Color-Neutrals-Black */,
                          fontSize: 14.sp,
                          fontFamily: AppFonts.brandoArabic,
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                    ],
                  ),
                  HeightSpace(8.h),

                  ValidatedTextField(
                    hint: "password".tr(),
                    leadingIconPath: AppAssets.key,
                    password: true,
                    allowUpperHint: false,
                    height: 44.h,
                    controller: _passwordController,
                    validator: AppValidators.validatePassword,
                  ),
                  HeightSpace(24.h),

                  PrimaryButton(
                    title: state is RegisterLoading
                        ? "registering".tr()
                        : "create_account".tr(),
                    onTap: state is RegisterLoading ? null : _handleRegister,
                    child: state is RegisterLoading
                        ? Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              SizedBox(
                                height: 20,
                                width: 20,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2,
                                  valueColor: AlwaysStoppedAnimation<Color>(
                                    Colors.white,
                                  ),
                                ),
                              ),
                              WidthSpace(8.w),
                              Text(
                                "registering".tr(),
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 14.sp,
                                  fontFamily: AppFonts.brandoArabic,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                            ],
                          )
                        : null,
                  ),
                ],
              ),
            ),
            HeightSpace(12.h),
            Row(
              mainAxisSize: MainAxisSize.min,
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.center,
              spacing: 4,
              children: [
                Text(
                  "already_have_account".tr(),
                  style: TextStyle(
                    color: const Color(0xFF4B5565) /* Color-Neutrals-600 */,
                    fontSize: 12.sp,
                    fontFamily: AppFonts.brandoArabic,
                    fontWeight: FontWeight.w500,
                  ),
                ),
                GestureDetector(
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => const LoginView(),
                      ),
                    );
                  },
                  child: Text(
                    "login".tr(),
                    style: TextStyle(
                      color: const Color(0xFF6A4690) /* Color-Brand-Main */,
                      fontSize: 12.sp,
                      fontFamily: AppFonts.brandoArabic,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ),
              ],
            ),
            HeightSpace(24.h),
            Container(
              width: screenWidth,
              child: Row(
                mainAxisSize: MainAxisSize.min,
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.center,
                spacing: 6,
                children: [
                  Expanded(
                    child: Container(
                      decoration: ShapeDecoration(
                        shape: RoundedRectangleBorder(
                          side: BorderSide(
                            width: 1,
                            strokeAlign: BorderSide.strokeAlignCenter,
                            color: const Color(
                              0xFFEEF2F6,
                            ) /* Color-Neutrals-100 */,
                          ),
                        ),
                      ),
                    ),
                  ),
                  WidthSpace(6.w),
                  Text(
                    "register_with".tr(),
                    textAlign: TextAlign.right,
                    style: TextStyle(
                      color: Colors.black /* Color-Neutrals-Black */,
                      fontSize: 12.sp,
                      fontFamily: AppFonts.brandoArabic,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  WidthSpace(6.w),
                  Expanded(
                    child: Container(
                      decoration: ShapeDecoration(
                        shape: RoundedRectangleBorder(
                          side: BorderSide(
                            width: 1,
                            strokeAlign: BorderSide.strokeAlignCenter,
                            color: const Color(
                              0xFFEEF2F6,
                            ) /* Color-Neutrals-100 */,
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            HeightSpace(16.h),
            Row(
              mainAxisSize: MainAxisSize.min,
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                // Google Sign In (Available on both iOS and Android)
                if (SocialLoginPlatform.showGoogleLogin)
                  GestureDetector(
                    onTap: () {
                      context.read<SocialAuthCubit>().signInWithGoogle();
                    },
                    child: Image.asset(AppAssets.google, width: 48.w),
                  ),

                // Add spacing between Google and Apple if Apple is shown
                if (SocialLoginPlatform.showGoogleLogin &&
                    SocialLoginPlatform.showAppleLogin)
                  WidthSpace(16.w),

                // Apple Sign In (iOS only) - Styled like Google button
                if (SocialLoginPlatform.showAppleLogin)
                  GestureDetector(
                    onTap: () {
                      context.read<SocialAuthCubit>().signInWithApple();
                    },
                    child: Container(
                      width: 48.w,
                      height: 48.w,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(8.r),
                        border: Border.all(
                          color: Colors.grey.shade300,
                          width: 1,
                        ),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withOpacity(0.1),
                            blurRadius: 4,
                            offset: const Offset(0, 2),
                          ),
                        ],
                      ),
                      child: Center(
                        child: SvgPicture.asset(
                          AppAssets.apple,
                          width: 24.w,
                          height: 24.w,
                        ),
                      ),
                    ),
                  ),

                // Twitter Sign In (hidden for now)
                if (SocialLoginPlatform.showTwitterLogin) ...[
                  WidthSpace(16.w),
                  GestureDetector(
                    onTap: () {
                      context.read<SocialAuthCubit>().signInWithTwitter();
                    },
                    child: Image.asset(AppAssets.twitter, width: 48.w),
                  ),
                ],
              ],
            ),
          ],
        );
      },
    );
  }
}
