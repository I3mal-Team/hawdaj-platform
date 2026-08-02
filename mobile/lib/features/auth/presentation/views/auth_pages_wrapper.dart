// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/environment_switcher_floating_button.dart';

class AuthPagesWrapper extends StatelessWidget {
  final List<Widget> children;
  const AuthPagesWrapper({
    super.key,
    required this.children,
    this.showEnvironmentSwitcher = true,
  });
  final bool showEnvironmentSwitcher;

  @override
  Widget build(BuildContext context) {
    final isKeyboardVisible = MediaQuery.of(context).viewInsets.bottom > 0;
    double screenWidth = MediaQuery.of(context).size.width;
    return Scaffold(
      backgroundColor: AppColors.bg,
      body: Stack(
        alignment: Alignment.center,
        children: [
          Image.asset(
            AppAssets.splashBG,
            width: double.infinity,
            height: double.infinity,
            fit: BoxFit.cover,
          ),
          Column(
            mainAxisAlignment: MainAxisAlignment.end,
            children: [
              // Conditionally show/hide the logo based on keyboard visibility
              if (!isKeyboardVisible)
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Image.asset(AppAssets.logoWithBgCamel, width: 174.w),
                    ],
                  ),
                ),
              // White container with input fields
              Container(
                constraints: BoxConstraints(
                  maxHeight:
                      MediaQuery.of(context).size.height -
                      MediaQuery.of(context).viewInsets.bottom,
                ),
                child: Stack(
                  clipBehavior: Clip.none,
                  children: [
                    Positioned(
                      top: -12.h,
                      child: SizedBox(
                        width: screenWidth,
                        child: Container(
                          margin: EdgeInsets.symmetric(horizontal: 16.w),
                          height: 100,
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(.3),

                            borderRadius: BorderRadius.only(
                              topLeft: Radius.circular(25.r),
                              topRight: Radius.circular(25.r),
                            ),
                          ),
                        ),
                      ),
                    ),
                    Container(
                      padding: EdgeInsets.only(
                        top: 32.h,
                        right: 16.w,
                        left: 16.w,
                        bottom: 23.h,
                      ),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: isKeyboardVisible
                            ? BorderRadius
                                  .zero // No rounded corners when keyboard is visible
                            : BorderRadius.only(
                                topLeft: Radius.circular(25.r),
                                topRight: Radius.circular(25.r),
                              ),
                      ),
                      width: double.infinity,
                      height: isKeyboardVisible
                          ? MediaQuery.of(context)
                                .size
                                .height // Full height when keyboard is visible
                          : null, // Default height when keyboard is hidden
                      child: SafeArea(
                        top: isKeyboardVisible ? true : false,
                        child: Container(
                          constraints: BoxConstraints(maxHeight: 600.h),
                          child: SingleChildScrollView(
                            child: Column(children: children),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
          if (showEnvironmentSwitcher)
            EnvironmentSwitcherFloatingButton(
              environmentSwitcherRoute: RoutesKeys.kEnvironmentSwitcher,
            ),
        ],
      ),
    );
  }
}
