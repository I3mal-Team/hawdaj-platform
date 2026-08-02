// import 'package:flutter/material.dart';
// import 'package:flutter_screenutil/flutter_screenutil.dart';
// import 'package:hawdaj/core/components/primary_button.dart';
// import 'package:hawdaj/core/components/seconday_button.dart';
// import 'package:hawdaj/core/components/spaces.dart';
// import 'package:hawdaj/core/locale/locale_cubit.dart';
// import 'package:hawdaj/core/styles/app_colors.dart';
// import 'package:hawdaj/core/styles/app_text_styles.dart';
// import 'package:hawdaj/core/utils/app_assets.dart';
// import 'package:hawdaj/features/trip/presentation/view/widgets/price_option_tile.dart';

// class LanguageBottomSheet extends StatelessWidget {
//   const LanguageBottomSheet({super.key});

//   @override
//   Widget build(BuildContext context) {
//     return Builder(
//       builder: (sheetCtx) {
//         return SingleChildScrollView(
//           padding: EdgeInsets.only(
//             bottom: MediaQuery.of(sheetCtx).viewInsets.bottom,
//           ),
//           child: Column(
//             mainAxisSize: MainAxisSize.min,
//             crossAxisAlignment: CrossAxisAlignment.start,
//             mainAxisAlignment: MainAxisAlignment.start,
//             children: [
//               Text(
//                 "قم باختيار اللغه المراد استخدام التطبيق بها ",
//                 style: AppTextStyles.font18Bold.copyWith(
//                   color: AppColors.lightGrey,
//                 ),
//               ),

//               HeightSpace(16.h),

//               GridView.count(
//                 shrinkWrap: true,
//                 physics: const NeverScrollableScrollPhysics(),
//                 crossAxisCount: 2,
//                 // crossAxisSpacing: 12.w,
//                 mainAxisSpacing: 12.h,
//                 childAspectRatio: 1.6,
//                 children: [
//                   PriceOptionTile(
//                     title: "العربية",
//                     isSelected: true,
//                     onTap: () {},
//                     imagePath: AppAssets.flags,
//                   ),
//                   PriceOptionTile(
//                     title: "العربية",
//                     isSelected: true,
//                     onTap: () {},
//                     imagePath: AppAssets.flags,
//                   ),
//                 ],
//               ),
//               HeightSpace(16.h),

//               Row(
//                 children: [
//                   Expanded(
//                     child: PrimaryButton(title: 'تغيير', onTap: () {}),
//                   ),
//                   WidthSpace(8.w),
//                   Expanded(
//                     child: SecondaryButton(title: 'إلغاء', onTap: () {}),
//                   ),
//                 ],
//               ),
//             ],
//           ),
//         );
//       },
//     );
//   }
// }
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/seconday_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/locale/locale_cubit.dart';
import 'package:hawdaj/core/managers/locale_cubit/locale_cubit.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/price_option_tile.dart';

class LanguageBottomSheet extends StatefulWidget {
  const LanguageBottomSheet({super.key});

  @override
  State<LanguageBottomSheet> createState() => _LanguageBottomSheetState();
}

class _LanguageBottomSheetState extends State<LanguageBottomSheet> {
  Locale? selectedLocale;

  @override
  void initState() {
    super.initState();
    // Get current language from LocaleCubit
    try {
      selectedLocale = context.read<LocaleCubit>().currentLocale;
    } catch (e) {
      selectedLocale = const Locale('ar'); // Default fallback
    }
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<LocaleCubit, LocaleState>(
      listener: (context, state) {
        if (state is LocaleLoaded) {
          setState(() {
            selectedLocale = state.locale;
          });
        } else if (state is LocaleError) {
          if (mounted) {
            showCustomFailureToast(state.message);
          }
        }
      },
      builder: (context, state) {
        return Container(
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
          ),
          child: SingleChildScrollView(
            padding: EdgeInsets.only(
              bottom: MediaQuery.of(context).viewInsets.bottom + 16.h,
              left: 16.w,
              right: 16.w,
              top: 16.h,
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Handle bar
                Center(
                  child: Container(
                    width: 40.w,
                    height: 4.h,
                    decoration: BoxDecoration(
                      color: AppColors.lightGrey,
                      borderRadius: BorderRadius.circular(2.h),
                    ),
                  ),
                ),

                HeightSpace(16.h),

                // Title
                Text(
                  "select_language_message".tr(),
                  style: AppTextStyles.font18Bold.copyWith(
                    color: AppColors.lightGrey,
                  ),
                ),

                HeightSpace(20.h),

                // Language Options Grid
                GridView.count(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  crossAxisCount: 2,
                  crossAxisSpacing: 12.w,
                  mainAxisSpacing: 12.h,
                  childAspectRatio: 1.5.w,
                  children: _buildLanguageOptions(),
                ),

                HeightSpace(24.h),

                // Action Buttons
                Row(
                  children: [
                    Expanded(
                      child: PrimaryButton(
                        title: 'change'.tr(),
                        onTap: _onChangeLanguage,
                      ),
                    ),
                    WidthSpace(12.w),
                    Expanded(
                      child: SecondaryButton(
                        title: 'cancel'.tr(),
                        onTap: () => Navigator.of(context).pop(),
                      ),
                    ),
                  ],
                ),

                HeightSpace(16.h),
              ],
            ),
          ),
        );
      },
    );
  }

  // Build language option tiles
  List<Widget> _buildLanguageOptions() {
    return LocaleCubit.supportedLocales.map((locale) {
      final isSelected = selectedLocale?.languageCode == locale.languageCode;
      final languageName =
          LocaleCubit.languageNames[locale.languageCode] ?? locale.languageCode;

      return PriceOptionTile(
        title: languageName,
        isSelected: isSelected,
        onTap: () => _onLanguageSelected(locale),
        imagePath: _getFlagAsset(locale.languageCode),
      );
    }).toList();
  }

  // Handle language selection
  void _onLanguageSelected(Locale locale) {
    setState(() {
      selectedLocale = locale;
    });
  }

  // Handle change language button press
  void _onChangeLanguage() async {
    if (selectedLocale == null) return;

    try {
      // Show loading dialog
      // if (mounted) {
      //   showDialog(
      //     context: context,
      //     barrierDismissible: false,
      //     builder: (context) =>
      //         const Center(child: CircularProgressIndicator()),
      //   );
      // }

      // Change language using LocaleCubit
      await context.read<LocaleCubit>().changeLanguage(
        context,
        selectedLocale!,
      );

      // Close loading dialog
      if (mounted && Navigator.of(context).canPop()) {
        Navigator.of(context).pop();
      }

      // Close bottom sheet
      if (mounted && Navigator.of(context).canPop()) {
        Navigator.of(context).pop();
      }

      // Show success message
      if (mounted) {
        showCustomSuccessToast('language_changed_successfully'.tr());
      }
    } catch (e) {
      // Close loading dialog if open
      if (mounted && Navigator.of(context).canPop()) {
        Navigator.of(context).pop();
      }

      // Show error message
      if (mounted) {
        showCustomFailureToast('failed_to_change_language'.tr());
      }
    }
  }

  // Get flag asset for language
  String _getFlagAsset(String languageCode) {
    switch (languageCode) {
      case 'ar':
        return AppAssets.sa;
      case 'en':
        return AppAssets.us;
      case 'ru':
        return AppAssets.ru;
      default:
        return AppAssets.cn;
    }
  }
}
