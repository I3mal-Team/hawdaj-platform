import 'package:hawdaj/core/managers/nav_bar_cubit/nav_bar_cubit.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

Future<T?> baseBottomSheet<T>({
  required BuildContext context,
  required Widget child,
  required bool hideNavBar,
  bool showDragHandle = true,
  String? title,
  bool showCloseButton = true,
}) async {
  final navBarCubit = context.read<NavBarCubit>();

  void handleNavBar(bool isVisible) {
    if (hideNavBar) {
      isVisible ? navBarCubit.showNavBar() : navBarCubit.hideNavBar();
    }
  }

  handleNavBar(false);

  return showModalBottomSheet<T>(
    context: context,
    useSafeArea: true,
    isScrollControlled: true, // 👈 ضروري علشان يقدر يتمدد فوق الكيبورد
    backgroundColor: AppColors.white,
    builder: (context) {
      return Padding(
        // 👇 هنا السحر: padding ديناميكي حسب الكيبورد
        padding: EdgeInsets.only(
          bottom: MediaQuery.of(context).viewInsets.bottom,
        ),
        child: AnimatedPadding(
          // 👌 حركة سلسة عند فتح الكيبورد أو إغلاقه
          duration: const Duration(milliseconds: 250),
          curve: Curves.easeOut,
          padding: EdgeInsets.only(
            left: 24,
            right: 24,
            bottom: 24,
            top: showDragHandle ? 8 : 24,
          ),
          child: SafeArea(
            child: SingleChildScrollView(
              physics: const ClampingScrollPhysics(),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  if (showDragHandle)
                    Center(
                      child: Container(
                        height: 4.h,
                        width: 80.w,
                        margin: EdgeInsets.only(bottom: 4.h),
                        decoration: BoxDecoration(
                          color: AppColors.dark50,
                          borderRadius: BorderRadius.circular(100),
                        ),
                      ),
                    ),
                  if (title != null)
                    Padding(
                      padding: const EdgeInsets.symmetric(vertical: 8),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            title,
                            style: AppTextStyles.font18Medium.copyWith(
                              color: AppColors.obsidianBlack,
                            ),
                          ),
                        ],
                      ),
                    ),
                  child,
                ],
              ),
            ),
          ),
        ),
      );
    },
  ).whenComplete(() => handleNavBar(true));
}
