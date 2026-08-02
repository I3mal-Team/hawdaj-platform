import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/add_property_form_cubit/add_property_form_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/drod_down-link.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/platform_color.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/platform_icon.dart';

class SocialLinksList extends StatelessWidget {
  const SocialLinksList({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<AddPropertyFormCubit, AddPropertyFormState>(
      builder: (context, formState) {
        final cubit = context.read<AddPropertyFormCubit>();
        final socialLinks = cubit.currentSocialLinks;

        if (socialLinks.isEmpty) {
          return Padding(
            padding: const EdgeInsets.all(16.0),
            child: Center(
              child: Text(
                'no_links_added'.tr(),
                style: AppTextStyles.font14Regular,
              ),
            ),
          );
        }

        return ListView.separated(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          itemCount: socialLinks.length,
          separatorBuilder: (_, __) => HeightSpace(8.h),
          itemBuilder: (context, index) {
            final platform = socialLinks[index]['platform']!;
            final link = socialLinks[index]['link']!;

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
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(platform, style: AppTextStyles.font16Bold),
                        HeightSpace(4.h),
                        Text(link, style: AppTextStyles.font10Regular),
                      ],
                    ),
                  ),
                  IconButton(
                    tooltip: 'delete_tooltip'.tr(),
                    onPressed: () {
                      cubit.removeSocialLink(platform);
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
                      final result = await baseBottomSheet(
                        title: 'update_social_link'.tr(),
                        context: context,
                        child: SocialMediaDropdown(
                          initialPlatform: platform,
                          availablePlatforms: cubit.availablePlatforms,
                          initialLink: link,

                          onSave: (p, l) {
                            cubit.addSocialLink(p, l);
                          },
                        ),
                        hideNavBar: true,
                      );

                      if (context.mounted && result == true) {
                        showCustomSuccessToast('social_link_updated'.tr());
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
        );
      },
    );
  }
}
