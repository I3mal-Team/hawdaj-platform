import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/managers/user_info_cubit/user_info_cubit.dart';

import 'package:hawdaj/core/styles/app_colors.dart';

import 'package:hawdaj/features/profile/data/model/profile_reponce.dart';
import 'package:hawdaj/features/profile/presentation/manager/edit_profile_cubit/edit_profile_cubit.dart';
import 'package:hawdaj/features/profile/presentation/manager/get_profile_cubit/get_profile_cubit.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/edit_profile_body.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_save_trip_done.dart';

class EditProfileView extends StatelessWidget {
  const EditProfileView({super.key, required this.profilePageResponse});
  final ProfilePageResponse profilePageResponse;

  @override
  Widget build(BuildContext context) {
    return BlocListener<EditProfileCubit, EditProfileState>(
      listener: (context, state) async {
        print("state ${state}");
        if (state.isSuccess) {
          context.read<UserInfoCubit>().updateUserData(state.updatedUser!);

          // إضافة delay قصير لإعطاء السيرفر وقت لتحديث قاعدة البيانات
          await Future.delayed(const Duration(milliseconds: 500));

          // إعادة تحميل بيانات البروفايل لضمان تحديث GetProfileCubit
          context.read<GetProfileCubit>().getProfile();

          showCustomSuccessToast('profile_update_success'.tr());
          baseBottomSheet(
            context: context,
            hideNavBar: false,
            child: BottomSheetTripSaveTripDone(
              onTap: () {
                context.pop(); // إغلاق الـ bottom sheet
                context.pop(true);
              },
              textButton: 'next'.tr(),
              title: 'profile_update_saved_title'.tr(),
            ),
          );

          //  context.pop(true);
        } else if (state.errorMessage != null) {
          showCustomFailureToast(
            state.errorMessage ?? 'profile_update_failed'.tr(),
          );
        }
      },
      child: BlocBuilder<UserInfoCubit, UserInfoState>(
        builder: (context, userState) {
          if (userState.loading) {
            return const Center(child: CircularProgressIndicator());
          }

          final editProfileCubit = context.read<EditProfileCubit>();
          if (userState.user != null && !editProfileCubit.state.isInitialized) {
            WidgetsBinding.instance.addPostFrameCallback((_) {
              editProfileCubit.initializeProfile(profilePageResponse);
            });
          }

          return Scaffold(
            body: EditProfileBody(
              userPhoto: profilePageResponse.data.personalData.photo,
            ),
            bottomNavigationBar:
                BlocBuilder<EditProfileCubit, EditProfileState>(
                  builder: (context, editState) {
                    final editProfileCubit = context.read<EditProfileCubit>();
                    return Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Row(
                        children: [
                          Expanded(
                            child: PrimaryButton(
                              padding: EdgeInsets.zero,
                              title: editState.isLoading
                                  ? 'saving'.tr()
                                  : 'save'.tr(),
                              onTap:
                                  (editState.isLoading ||
                                      !editState.isFormValid)
                                  ? null
                                  : () {
                                      editProfileCubit.submit();
                                    },
                              height: 44.h,
                            ),
                          ),
                          WidthSpace(12.w),
                          Expanded(
                            child: PrimaryButton(
                              padding: EdgeInsets.zero,
                              title: 'close'.tr(),
                              height: 44.h,
                              backgroundColor: const Color(0xffF5F7F8),
                              textColor: AppColors.uiBlack,
                              onTap: () {
                                if (context.canPop()) context.pop();
                              },
                            ),
                          ),
                        ],
                      ),
                    );
                  },
                ),
          );
        },
      ),
    );
  }
}
