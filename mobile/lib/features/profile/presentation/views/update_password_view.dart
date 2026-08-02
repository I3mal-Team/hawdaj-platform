import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/profile/presentation/manager/update_password_cubit/update_password_cubit.dart';
import 'dart:ui' as ui;

import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';

class UpdatePasswordView extends StatelessWidget {
  const UpdatePasswordView({super.key});

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<UpdatePasswordCubit>();

    return Scaffold(
      body: BlocConsumer<UpdatePasswordCubit, UpdatePasswordState>(
        listener: (context, state) {
          if (state is UpdatePasswordError) {
            showCustomFailureToast(state.message);
          } else if (state is UpdatePasswordSuccess) {
            showCustomSuccessToast('password_update_success'.tr());
            Navigator.pop(context);
          }
        },
        builder: (context, state) {
          return CustomScrollView(
            slivers: [
              SliverToBoxAdapter(
                child: TripStartAppBar(
                  title: 'change_password_button'.tr(),
                  showimage: false,
                ),
              ),
              SliverFillRemaining(
                hasScrollBody: false,
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        "current_password_label".tr(),
                        style: AppTextStyles.font14Regular,
                      ),
                      CustomTextField(
                        controller: cubit.currentPasswordController,
                        hint: "current_password_label".tr(),
                        leadingIconPath: AppAssets.key,
                        password: true,
                      ),
                      HeightSpace(20.h),
                      Text(
                        "new_password_label".tr(),
                        style: AppTextStyles.font14Regular,
                      ),
                      CustomTextField(
                        controller: cubit.passwordController,
                        hint: "new_password_label".tr(),
                        leadingIconPath: AppAssets.key,
                        password: true,
                      ),
                      HeightSpace(20.h),
                      Text(
                        'confirm_password_label'.tr(),
                        style: AppTextStyles.font14Regular,
                      ),
                      CustomTextField(
                        controller: cubit.passwordconfirmationController,
                        hint: "confirm_password_label".tr(),
                        leadingIconPath: AppAssets.key,
                        password: true,
                      ),
                    ],
                  ),
                ),
              ),
            ],
          );
        },
      ),
      bottomNavigationBar: Padding(
        padding: const EdgeInsets.all(8.0),
        child: BlocBuilder<UpdatePasswordCubit, UpdatePasswordState>(
          builder: (context, state) {
            return PrimaryButton(
              title: state is UpdatePasswordLoading
                  ? "saving".tr()
                  : "change_password_title".tr(),
              onTap: state is! UpdatePasswordLoading
                  ? () => context.read<UpdatePasswordCubit>().updatePassword()
                  : null,
              active: state is! UpdatePasswordLoading,
              height: 56.h,
              width: double.infinity,
            );
          },
        ),
      ),
    );
  }
}
