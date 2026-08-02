import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/custom_check_box_circle.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/app_router.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/auth/presentation/views/auth_pages_wrapper.dart';
import 'package:hawdaj/features/auth/presentation/views/register_view.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/login_cubit/login_cubit.dart';
import 'package:hawdaj/features/auth/presentation/manager/cubits/social_auth_cubit/social_auth_cubit.dart';
import 'package:hawdaj/features/auth/utils/social_login_platform.dart';

double get screenWidth => MediaQuery.of(parentKey.currentContext!).size.width;
// screen height
double get screenHeight => MediaQuery.of(parentKey.currentContext!).size.height;

class LoginView extends StatefulWidget {
  const LoginView({super.key});

  @override
  State<LoginView> createState() => _LoginViewState();
}

class _LoginViewState extends State<LoginView> {
  bool rememberMe = false;
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _handleLogin() {
    if (_emailController.text.trim().isEmpty) {
      showCustomFailureToast('enter_email'.tr());

      return;
    }

    if (_passwordController.text.trim().isEmpty) {
      showCustomFailureToast("enter_password".tr());

      return;
    }

    context.read<LoginCubit>().login(
      email: _emailController.text.trim(),
      password: _passwordController.text.trim(),
    );
  }

  @override
  Widget build(BuildContext context) {
    return MultiBlocListener(
      listeners: [
        BlocListener<LoginCubit, LoginState>(
          listener: (context, state) {
            if (state is LoginSuccess) {
              // showCustomSuccessToast(
              //   '${"welcome_user".tr()} ${state.user.fullName}',
              // );

              // Navigate to home after successful login
              context.go(RoutesKeys.kHomeView);
            } else if (state is LoginError) {
              showCustomFailureToast('${"welcome_user".tr()} ${state.message}');
            }
          },
        ),
        BlocListener<SocialAuthCubit, SocialAuthState>(
          listener: (context, state) {
            if (state is SocialAuthSuccess) {
              showCustomSuccessToast(
                '${"welcome_user".tr()} ${state.user.fullName}',
              );

              // Navigate to home after successful social login
              context.go(RoutesKeys.kHomeView);
            } else if (state is SocialAuthError) {
              showCustomFailureToast('${"welcome_user".tr()} ${state.message}');
            }
          },
        ),
      ],
      child: BlocBuilder<LoginCubit, LoginState>(
        builder: (context, loginState) {
          return BlocBuilder<SocialAuthCubit, SocialAuthState>(
            builder: (context, socialState) {
              final isLoading =
                  loginState is LoginLoading ||
                  socialState is SocialAuthLoading;
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
                              "login_title".tr(),
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

                        CustomTextField(
                          allowUpperHint: false,
                          hint: "email".tr(),
                          leadingIconPath: AppAssets.sms,
                          height: 44.h,
                          controller: _emailController,
                        ),
                        HeightSpace(16.h),

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

                        CustomTextField(
                          hint: "password".tr(),
                          leadingIconPath: AppAssets.key,
                          password: true,
                          allowUpperHint: false,
                          height: 44.h,
                          controller: _passwordController,
                        ),
                        HeightSpace(16.h),

                        // Remember me and forgot password row
                        Row(
                          mainAxisAlignment: MainAxisAlignment.end,
                          children: [
                            // Row(
                            //   children: [
                            //     // CustomCheckBoxCircleFill(
                            //     //   borderRadius: 5.r,
                            //     //   active: true,
                            //     // ),
                            //     WidthSpace(4.w),
                            //     Text(
                            //       "remember_me".tr(),
                            //       textAlign: TextAlign.right,
                            //       style: TextStyle(
                            //         color:
                            //             Colors.black /* Color-Neutrals-Black */,
                            //         fontSize: 14.sp,
                            //         fontFamily: AppFonts.brandoArabic,
                            //         fontWeight: FontWeight.w500,
                            //       ),
                            //     ),
                            //   ],
                            // ),
                            GestureDetector(
                              onTap: () {
                                // Navigate to forgot password screen
                                context.push(RoutesKeys.kForgotPasswordView);
                              },
                              child: Text(
                                "forgot_password".tr(),
                                style: TextStyle(
                                  color: const Color(
                                    0xFF6A4690,
                                  ) /* Color-Brand-Main */,
                                  fontSize: 14.sp,
                                  fontFamily: AppFonts.brandoArabic,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                            ),
                          ],
                        ),
                        HeightSpace(24.h),

                        PrimaryButton(
                          title: isLoading ? "logging_in".tr() : "login".tr(),
                          onTap: isLoading ? null : _handleLogin,
                        ),
                        HeightSpace(12.h),

                        Row(
                          mainAxisSize: MainAxisSize.min,
                          mainAxisAlignment: MainAxisAlignment.center,
                          crossAxisAlignment: CrossAxisAlignment.center,
                          spacing: 4,
                          children: [
                            Text(
                              "no_account".tr(),
                              style: TextStyle(
                                color: const Color(
                                  0xFF4B5565,
                                ) /* Color-Neutrals-600 */,
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
                                    builder: (context) => const RegisterView(),
                                  ),
                                );
                              },
                              child: Text(
                                "create_account".tr(),
                                style: TextStyle(
                                  color: const Color(
                                    0xFF6A4690,
                                  ) /* Color-Brand-Main */,
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
                                        strokeAlign:
                                            BorderSide.strokeAlignCenter,
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
                                "login_with".tr(),
                                textAlign: TextAlign.right,
                                style: TextStyle(
                                  color:
                                      Colors.black /* Color-Neutrals-Black */,
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
                                        strokeAlign:
                                            BorderSide.strokeAlignCenter,
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
                                  context
                                      .read<SocialAuthCubit>()
                                      .signInWithGoogle();
                                },
                                child: Image.asset(
                                  AppAssets.google,
                                  width: 48.w,
                                ),
                              ),

                            // Add spacing between Google and Apple if Apple is shown
                            if (SocialLoginPlatform.showGoogleLogin &&
                                SocialLoginPlatform.showAppleLogin)
                              WidthSpace(16.w),

                            // Apple Sign In (iOS only) - Styled like Google button
                            if (SocialLoginPlatform.showAppleLogin)
                              GestureDetector(
                                onTap: () {
                                  context
                                      .read<SocialAuthCubit>()
                                      .signInWithApple();
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
                                  context
                                      .read<SocialAuthCubit>()
                                      .signInWithTwitter();
                                },
                                child: Image.asset(
                                  AppAssets.twitter,
                                  width: 48.w,
                                ),
                              ),
                            ],
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              );
            },
          );
        },
      ),
    );
  }
}
