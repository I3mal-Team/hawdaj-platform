import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/restaurants/presentation/manager/menu_cubit/menu_cubit.dart';
import 'package:hawdaj/features/restaurants/presentation/view/widgets/menu_list_section.dart';

class ListOfSuppliesSection extends StatelessWidget {
  const ListOfSuppliesSection({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<MenuCubit, MenuState>(
      listener: (context, state) {
        if (state is MenuDownloadSuccess) {
          showCustomSuccessToast("download_success".tr());
        } else if (state is MenuDownloadFailure) {
          showCustomFailureToast(state.message);
        }
      },
      builder: (context, state) {
        final isDownloading = state is MenuDownloadInProgress;

        return Container(
          width: double.infinity,
          height: 56.h,
          padding: const EdgeInsets.all(8),
          decoration: ShapeDecoration(
            color: Colors.white,
            shape: RoundedRectangleBorder(
              side: BorderSide(width: 1, color: const Color(0xFFEEF2F6)),
              borderRadius: BorderRadius.circular(10),
            ),
          ),
          child: Row(
            children: [
              Image.asset(AppAssets.menuBoard, width: 40.w, height: 40.h),
              Text(
                "title".tr(),
                style: AppTextStyles.font12Bold.copyWith(
                  color: AppColors.black,
                ),
              ),
              WidthSpace(8.w),
              Text(
                "( 5MB )",
                style: AppTextStyles.font12Regular.copyWith(
                  color: AppColors.mainGrey,
                ),
              ),
              Expanded(
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    GestureDetector(
                      onTap: isDownloading
                          ? null
                          : () {
                              context.read<MenuCubit>().downloadMenu();
                            },
                      child: Image.asset(
                        AppAssets.import,
                        width: 40.w,
                        height: 40.h,
                        color: isDownloading ? AppColors.mainGrey : null,
                      ),
                    ),
                    GestureDetector(
                      onTap: () {
                        baseBottomSheet(
                          context: context,
                          title: "sheet_title".tr(),
                          hideNavBar: true,
                          child: BlocProvider.value(
                            value: context.read<MenuCubit>(),
                            child: Column(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                SizedBox(height: 16.h),
                                SizedBox(
                                  height: 400.h,
                                  child: const MenuListSection(),
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                      child: Image.asset(
                        AppAssets.buttons,
                        width: 40.w,
                        height: 40.h,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}
