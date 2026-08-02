import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/profile/presentation/manager/edit_profile_cubit/edit_profile_cubit.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/user_image.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/add_tour_guide_details_view_body.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/drod_down-link.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/platform_color.dart'
    show platformColor;
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/platform_icon.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_dealt.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';

class EditProfileBody extends StatelessWidget {
  const EditProfileBody({super.key, this.userPhoto});
  final String? userPhoto;

  @override
  Widget build(BuildContext context) {
    final editProfileCubit = context.read<EditProfileCubit>();

    return BlocBuilder<EditProfileCubit, EditProfileState>(
      builder: (context, editState) {
        final nonEmptyLinks = editState.socialLinks.entries
            .where((e) => e.value.trim().isNotEmpty)
            .toList();

        return CustomScrollView(
          slivers: [
            SliverToBoxAdapter(
              child: TripStartAppBar(title: 'profile_title'.tr()),
            ),

            SliverPadding(
              padding: EdgeInsets.all(16.w),
              sliver: SliverToBoxAdapter(
                child: Column(
                  children: [
                    UserImage(userPhoto: userPhoto),
                    HeightSpace(24.h),
                    Row(
                      children: [
                        Text(
                          'personal_info'.tr(),
                          style: TextStyle(
                            color: const Color(0xFF4B5565),
                            fontSize: 16.sp,
                            fontFamily: AppFonts.theYearOfTheCamel,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                      ],
                    ),
                    HeightSpace(12.h),

                    CustomTextField(
                      allowUpperHint: false,
                      hint: 'first_name_hint'.tr(),
                      label: 'first_name_label'.tr(),
                      leadingIconPath: AppAssets.profile,
                      height: 44.h,
                      controller: editProfileCubit.firstNameController,
                    ),
                    HeightSpace(16.h),

                    CustomTextField(
                      allowUpperHint: false,
                      hint: 'last_name_hint'.tr(),
                      label: 'last_name_label'.tr(),
                      leadingIconPath: AppAssets.profile,
                      height: 44.h,
                      controller: editProfileCubit.lastNameController,
                    ),
                    HeightSpace(16.h),

                    CustomTextField(
                      allowUpperHint: false,
                      hint: 'email_hint'.tr(),
                      label: 'email_label'.tr(),
                      leadingIconPath: AppAssets.sms,
                      height: 44.h,
                      controller: editProfileCubit.emailController,
                      enabled: false,
                    ),
                    HeightSpace(16.h),
                    Row(
                      children: [
                        Text(
                          'gender'.tr(),

                          style: TextStyle(
                            color: Colors.black /* Color-Neutrals-Black */,
                            fontSize: 14,
                            fontFamily: 'Brando Arabic',
                            fontWeight: FontWeight.w400,
                            height: 1.43,
                          ),
                        ),
                      ],
                    ),
                    HeightSpace(8.h),

                    GestureDetector(
                      onTap: () =>
                          editProfileCubit.showGenderSelection(context),
                      child: Container(
                        height: 44.h,
                        decoration: BoxDecoration(
                          border: Border.all(color: const Color(0xFFE5E7EB)),
                          borderRadius: BorderRadius.circular(8.r),
                        ),
                        child: Row(
                          children: [
                            Padding(
                              padding: EdgeInsets.symmetric(horizontal: 12.w),
                              child: Image.asset(
                                AppAssets.tagUser,
                                width: 20.w,
                                height: 20.h,
                              ),
                            ),
                            Expanded(
                              child: Text(
                                editState.genderDisplayText,
                                style: TextStyle(
                                  color: editState.gender == null
                                      ? const Color(0xFF9AA3B2)
                                      : Colors.black,
                                  fontSize: 14.sp,
                                  fontFamily: AppFonts.brandoArabic,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                            ),
                            Padding(
                              padding: EdgeInsets.symmetric(horizontal: 12.w),
                              child: Icon(
                                Icons.keyboard_arrow_down,
                                color: const Color(0xFF9AA3B2),
                                size: 20.sp,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                    HeightSpace(16.h),

                    CustomTextField(
                      allowUpperHint: false,
                      hint: 'phone_hint'.tr(),
                      label: 'phone_label'.tr(),
                      leadingIconPath: AppAssets.call,
                      height: 44.h,
                      controller: editProfileCubit.phoneController,
                      isPhone: true,
                    ),
                    HeightSpace(24.h),
                    HeightSpace(16.h),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Expanded(
                          child: Text(
                            'add_social_link_title'.tr(),

                            style: AppTextStyles.font16Bold,
                          ),
                        ),
                        Flexible(
                          child: PrimaryButton(
                            width: 100.w,
                            height: 32.h,
                            padding: EdgeInsets.zero,
                            title: 'add_button'.tr(),
                            iconPath: AppAssets.add,
                            backgroundColor: const Color(0xFFF2EBF6),
                            textColor: const Color(0xFF6A4690),

                            onTap: () async {
                              final result = await baseBottomSheet(
                                title: 'add_social_link_title'.tr(),
                                context: context,
                                child: BlocProvider.value(
                                  value: context.read<EditProfileCubit>(),
                                  child: SocialMediaDropdown(
                                    onSave: (platform, link) {
                                      context
                                          .read<EditProfileCubit>()
                                          .addSocialLink(platform, link);
                                    },
                                    onEdit: (oldPlatform, newPlatform, link) {
                                      context
                                          .read<EditProfileCubit>()
                                          .editSocialLink(
                                            oldPlatform: oldPlatform,
                                            newPlatform: newPlatform,
                                            link: link,
                                          );
                                      context.pop();
                                    },
                                  ),
                                ),
                                hideNavBar: false,
                              );

                              if (context.mounted && result == true) {
                                showCustomSuccessToast(
                                  'social_link_added'.tr(),
                                );
                              }
                            },
                          ),
                        ),
                      ],
                    ),

                    HeightSpace(8.h),
                    if (nonEmptyLinks.isEmpty)
                      Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Center(
                          child: Text(
                            'no_links_added'.tr(),
                            style: AppTextStyles.font14Regular,
                          ),
                        ),
                      )
                    else
                      ListView.separated(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: nonEmptyLinks.length,
                        separatorBuilder: (_, __) => HeightSpace(8.h),
                        itemBuilder: (context, index) {
                          final entry = nonEmptyLinks[index];
                          final platformRaw = entry.key;
                          final link = entry.value;
                          final platform = normalizePlatformKey(platformRaw);

                          return Container(
                            width: double.infinity,
                            padding: const EdgeInsets.all(8),
                            decoration: ShapeDecoration(
                              color: const Color(0xFFF8FAFC),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(8),
                              ),
                            ),
                            child: Row(
                              children: [
                                Icon(
                                  platformIcon(platform),
                                  color: platformColor(platform),
                                  size: 36,
                                ),
                                WidthSpace(10.w),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        platform,
                                        style: AppTextStyles.font16Bold,
                                      ),
                                      HeightSpace(4.h),
                                      Text(
                                        link,
                                        style: AppTextStyles.font10Regular,
                                      ),
                                    ],
                                  ),
                                ),
                                IconButton(
                                  tooltip: 'delete'.tr(),
                                  onPressed: () async {
                                    final cubit = context
                                        .read<EditProfileCubit>();

                                    final result = await baseBottomSheet(
                                      title: 'update_social_link'.tr(),
                                      context: context,
                                      child: BottomSheetTripDealt(
                                        title: 'delete_social_link'.tr(),
                                        message: 'delete_social_link_msg'.tr(),
                                        onTapConfirm: () {
                                          cubit.removeSocialLink(platform);
                                          Navigator.pop(context);
                                          showCustomSuccessToast(
                                            'social_link_deleted'.tr(),
                                          );
                                        },
                                        onTapCancel: () {
                                          Navigator.pop(context);
                                        },
                                        confirm: 'confirm'.tr(),
                                        cancel: 'cancel'.tr(),
                                      ),
                                      hideNavBar: true,
                                    );
                                  },
                                  icon: Image.asset(
                                    AppAssets.buttonsRemove,
                                    width: 24.w,
                                    height: 24.h,
                                  ),
                                ),
                                WidthSpace(2.w),
                                IconButton(
                                  tooltip: 'update'.tr(),
                                  onPressed: () async {
                                    final cubit = context
                                        .read<EditProfileCubit>();

                                    final result = await baseBottomSheet(
                                      title: 'update_social_link'.tr(),
                                      context: context,
                                      child: SocialMediaDropdown(
                                        initialPlatform: platform,
                                        initialLink: link,
                                        onSave: (p, l) {
                                          // مش هيحصل غالبًا هنا لأننا في حالة تحديث
                                          cubit.addSocialLink(p, l);
                                        },
                                        onEdit:
                                            (
                                              oldPlatform,
                                              newPlatform,
                                              updatedLink,
                                            ) {
                                              cubit.editSocialLink(
                                                oldPlatform: oldPlatform,
                                                newPlatform: newPlatform,
                                                link: updatedLink,
                                              );
                                            },
                                      ),
                                      hideNavBar: true,
                                    );

                                    if (context.mounted && result == true) {
                                      showCustomSuccessToast(
                                        'social_link_updated'.tr(),
                                      );
                                    }
                                  },
                                  icon: Image.asset(
                                    AppAssets.buttonsEdit,
                                    width: 24.w,
                                    height: 24.h,
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                      ),
                  ],
                ),
              ),
            ),
          ],
        );
      },
    );
  }
}
