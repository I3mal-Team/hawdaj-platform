import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/multi_select_drop_down_field.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/update_guide_photo_cubit/update_guide_photo_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/fetch_languages_cubit/fetch_languages_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/fetch_region_cubit/fetch_region_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/tour_guide_form_cubit/tour_guide_form_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/add_guide_image_button.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/drod_down-link.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/platform_color.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/platform_icon.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/section_field.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';
import 'package:hawdaj/core/components/spaces.dart';

class AddTourGuideDetailsViewBody extends StatelessWidget {
  const AddTourGuideDetailsViewBody({super.key});

  @override
  Widget build(BuildContext context) {
    return CustomScrollView(
      slivers: [
        SliverToBoxAdapter(
          child: TripStartAppBar(title: "tour_guide_profile".tr()),
        ),

        SliverToBoxAdapter(
          child: Padding(
            padding: const EdgeInsets.all(8.0),
            child: BlocBuilder<TourGuideFormCubit, TourGuideFormState>(
              builder: (context, formState) {
                return Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    BlocConsumer<UpdateGuidePhotoCubit, UpdateGuidePhotoState>(
                      listener: (context, s) {
                        if (s is UpdateGuidePhotoSuccess) {
                          final url =
                              s.image; // اسم الحقل في Success state عندك
                          // حدّث الفورم: خزن الـ URL وامسح الصورة المحلية
                          final form = context.read<TourGuideFormCubit>();
                          form.emit(
                            form.state.copyWith(
                              imageUrl: url,
                              resetImageFile:
                                  false, // لو عامل الدعم ده في copyWith
                            ),
                          );
                          showCustomSuccessToast("image_update_success".tr());
                        } else if (s is UpdateGuidePhotoFailure) {
                          showCustomFailureToast(s.message);
                        }
                      },
                      builder: (context, s) {
                        final isUploading = s is UpdateGuidePhotoLoading;
                        final formState = context
                            .watch<TourGuideFormCubit>()
                            .state;

                        return Stack(
                          children: [
                            // زر/صندوق الصورة
                            Opacity(
                              opacity: isUploading ? 0.6 : 1.0,
                              child: AbsorbPointer(
                                absorbing:
                                    isUploading, // امنع الضغط أثناء الرفع
                                child: Center(
                                  child: AddUserImageButton(
                                    onPickImage: () async {
                                      // 1) اختَر صورة محليًا واحفظها في الفورم
                                      await context
                                          .read<TourGuideFormCubit>()
                                          .setImageFromGallery();

                                      // 2) ابعت الصورة للـ API
                                      final file = context
                                          .read<TourGuideFormCubit>()
                                          .state
                                          .imageFile;
                                      if (file == null) {
                                        showCustomFailureToast(
                                          "no_image_selected".tr(),
                                        );
                                        return;
                                      }
                                      context
                                          .read<UpdateGuidePhotoCubit>()
                                          .updateGuidePhoto(image: file);
                                    },
                                    pickedFile: formState.imageFile,
                                    avatarLink: formState.imageUrl.isEmpty
                                        ? null
                                        : "${formState.imageUrl}?v=${DateTime.now().millisecondsSinceEpoch}",
                                  ),
                                ),
                              ),
                            ),

                            // لودينغ فوق الصورة
                            if (isUploading)
                              const Positioned.fill(
                                child: Center(
                                  child: CircularProgressIndicator(),
                                ),
                              ),
                          ],
                        );
                      },
                    ),

                    SectionField(
                      label: "guide_name_label".tr(),
                      child: CustomTextField(
                        hint: 'enter_name_hint'.tr(),
                        leadingIconPath: AppAssets.profile,
                        initialValue: formState.name ?? '',
                        onChange: (v) =>
                            context.read<TourGuideFormCubit>().updateName(v),
                      ),
                    ),

                    SectionField(
                      label: "nickname_label".tr(),
                      child: CustomTextField(
                        hint: 'enter_nickname_hint'.tr(),
                        leadingIconPath: AppAssets.profile,
                        initialValue: formState.nickName ?? '',
                        onChange: (v) => context
                            .read<TourGuideFormCubit>()
                            .updateNickName(v),
                      ),
                    ),

                    SectionField(
                      label: "description_label".tr(),
                      child: CustomTextField(
                        hint: 'enter_description_hint'.tr(),
                        leadingIconPath: AppAssets.infoGrey,
                        initialValue: formState.description ?? '',
                        svgColor: AppColors.grey,
                        maxLines: 5,
                        height: 100.h,
                        onChange: (v) => context
                            .read<TourGuideFormCubit>()
                            .updateDescription(v),
                      ),
                    ),

                    SectionField(
                      label: "experience_label".tr(),
                      child: CustomTextField(
                        hint: 'enter_experience_hint'.tr(),
                        inputType: TextInputType.number,
                        leadingIconPath: AppAssets.hashtag,
                        initialValue: formState.experience ?? '',
                        onChange: (v) => context
                            .read<TourGuideFormCubit>()
                            .updateExperience(v),
                      ),
                    ),

                    /// ✅ اختيار المناطق
                    BlocBuilder<FetchRegionCubit, FetchRegionState>(
                      builder: (context, state) {
                        if (state is FetchRegionLoaded) {
                          final items = state.region
                              .map(
                                (r) => GlobalPopUpData(id: r.id, title: r.name),
                              )
                              .toList();

                          return SectionField(
                            label: "regions_label".tr(),
                            child: MultiSelectDropDownField(
                              hint: 'choose_regions_hint'.tr(),
                              items: items,
                              leadingIconPath: AppAssets.gpsPng,
                              selectedIds: formState.selectedRegionIds,
                              onChanged: (newSelected) {
                                context
                                    .read<TourGuideFormCubit>()
                                    .updateSelectedRegions(newSelected);
                              },
                            ),
                          );
                        } else if (state is FetchRegionLoading) {
                          return const Center(
                            child: CircularProgressIndicator(),
                          );
                        } else {
                          return Text('fetch_regions_failed'.tr());
                        }
                      },
                    ),

                    /// ✅ اختيار اللغات
                    BlocBuilder<FetchLanguagesCubit, FetchLanguagesState>(
                      builder: (context, state) {
                        if (state is FetchLanguagesSuccess) {
                          final items = state.languages
                              .map(
                                (r) => GlobalPopUpData(id: r.id, title: r.name),
                              )
                              .toList();

                          return SectionField(
                            label: "languages_label".tr(),
                            child: MultiSelectDropDownField(
                              hint: 'choose_languages_hint'.tr(),
                              items: items,
                              leadingIconPath: AppAssets.tagUser,
                              selectedIds: formState.selectedLanguageIds,
                              onChanged: (newSelected) {
                                context
                                    .read<TourGuideFormCubit>()
                                    .updateSelectedLanguages(newSelected);
                              },
                            ),
                          );
                        } else if (state is FetchLanguagesLoading) {
                          return const Center(
                            child: CircularProgressIndicator(),
                          );
                        } else {
                          return Text('fetch_languages_failed'.tr());
                        }
                      },
                    ),

                    // ================== Social Links ==================
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      crossAxisAlignment: CrossAxisAlignment.start,
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
                            backgroundColor: Color(0xFFF2EBF6),
                            iconPath: AppAssets.add,
                            textColor: AppColors.primary,

                            title: 'add_button'.tr(),
                            onTap: () async {
                              final cubit = context.read<TourGuideFormCubit>();

                              final result = await baseBottomSheet(
                                title: 'social_link_added'.tr(),
                                context: context,
                                child: SocialMediaDropdown(
                                  onSave: (platform, link) {
                                    cubit.addSocialLink(platform, link);
                                  },
                                  onEdit: (oldPlatform, newPlatform, link) {
                                    cubit.editSocialLink(
                                      oldPlatform: oldPlatform,
                                      newPlatform: newPlatform,
                                      link: link,
                                    );
                                  },
                                ),
                                hideNavBar: true,
                              );

                              if (context.mounted && result == true) {
                                showCustomSuccessToast(
                                  'تم اضافة رابط التواصل بنجاح',
                                );
                              }
                            },
                          ),
                        ),
                      ],
                    ),

                    HeightSpace(8.h),

                    // ✅ عرض الروابط المضافة من الCubit
                    if (formState.socialLinks.isEmpty)
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
                        itemCount: formState.socialLinks.length,
                        separatorBuilder: (_, __) => HeightSpace(8.h),
                        itemBuilder: (context, index) {
                          final platformRaw = formState.socialLinks.keys
                              .elementAt(index);
                          final link = formState.socialLinks[platformRaw] ?? '';
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
                                  tooltip: 'delete_tooltip'.tr(),
                                  onPressed: () {
                                    context
                                        .read<TourGuideFormCubit>()
                                        .removeSocialLink(platform);
                                  },
                                  icon: Image.asset(
                                    AppAssets.buttonsRemove,
                                    width: 24.w,
                                    height: 24.h,
                                  ),
                                ),
                                WidthSpace(8.w),
                                IconButton(
                                  tooltip: 'update_tooltip'.tr(),
                                  onPressed: () async {
                                    final cubit = context
                                        .read<TourGuideFormCubit>();

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
                );
              },
            ),
          ),
        ),
      ],
    );
  }
}

String normalizePlatformKey(String key) {
  switch (key.toLowerCase()) {
    case 'x':
    case 'twitter':
      return 'X';
    case 'instagram':
      return 'Instagram';
    case 'linkedin':
      return 'LinkedIn';
    case 'youtube':
      return 'YouTube';
    case 'tiktok':
      return 'TikTok';
    case 'personal_account':
      return 'Personal Site';
    default:
      return key; // لو فيه مفتاح مش معروف سيبه زي ما هو
  }
}
