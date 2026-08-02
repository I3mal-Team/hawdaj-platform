import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';

import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';

void showLoginRequiredBottomSheet(BuildContext context) {
  baseBottomSheet(
    context: context,
    child: const LoginRequiredBottomSheetContent(),
    hideNavBar: true,
    showDragHandle: false,
  );
}

class LoginRequiredBottomSheetContent extends StatelessWidget {
  const LoginRequiredBottomSheetContent({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Stack(
          alignment: Alignment.bottomCenter,
          clipBehavior: Clip.none,
          children: [
            Image.asset(
              AppAssets.addUser,
              width: 250.w,
              height: MediaQuery.of(context).size.height * 0.35,
              fit: BoxFit.cover,
            ),
            Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              height: 100.h,
              child: Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: [
                      AppColors.white.withValues(alpha: 0),
                      AppColors.white.withValues(alpha: 0.9),
                      AppColors.white.withValues(alpha: 0.9),
                      AppColors.white,
                      AppColors.white,
                      AppColors.white,
                    ],
                  ),
                ),
              ),
            ),
            Positioned(
              bottom: 5.h,
              child: Text(
                "login".tr(),
                style: TextStyle(
                  color: Colors.black /* Color-Neutrals-Black */,
                  fontSize: 20.sp,
                  // /fontFamily: 'The Year of The Camel',
                  fontFamily: AppFonts.theYearOfTheCamel,
                  fontWeight: FontWeight.w900,
                  height: 1.50,
                  letterSpacing: -0.50,
                ),
              ),

              // TitleText(
              //   "login".tr(),
              //   weight: FontWeight.w700,
              //   color: AppColors.dark,
              //   size: 22,
              // ),
            ),
          ],
        ),
        HeightSpace(8.h),
        Text(
          "you_cannot_get_this_service_without_login".tr(),
          style: TextStyle(
            color: const Color(0xFF4B5565) /* Color-Neutrals-600 */,
            fontSize: 14.sp,
            fontFamily: AppFonts.brandoArabic,
            fontWeight: FontWeight.w400,
            height: 1.43,
          ),
        ),

        HeightSpace(24.h),
        Row(
          children: [
            Expanded(
              child: PrimaryButton(
                height: 44,
                padding: EdgeInsets.zero,
                title: "login".tr(),
                backgroundColor: const Color(0xFF6A4690),
                borderRadius: 8.r,
                onTap: () => go(RoutesKeys.kLoginView, context),
              ),
            ),
            WidthSpace(12.w),
            Expanded(
              child: PrimaryButton(
                padding: EdgeInsets.zero,
                height: 44.h,
                title: "later".tr(),
                backgroundColor: Color(0xffF5F7F8),
                textColor: AppColors.uiBlack,
                borderRadius: 8.r,

                active: true,
                onTap: () {
                  Navigator.pop(context);
                },
              ),
            ),
          ],
        ),
      ],
    );
  }
}
