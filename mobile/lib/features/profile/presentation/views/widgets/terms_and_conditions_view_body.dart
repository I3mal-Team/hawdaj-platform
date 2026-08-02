import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';

class TermsAndConditionsViewBody extends StatelessWidget {
  const TermsAndConditionsViewBody({super.key});

  @override
  Widget build(BuildContext context) {
    return CustomScrollView(
      slivers: [
        SliverToBoxAdapter(child: TripStartAppBar(title: 'terms_title'.tr())),
        SliverFillRemaining(
          hasScrollBody: false,
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "terms_section_intro".tr(),
                  style: AppTextStyles.font14Regular,
                ),
                Text(
                  "terms_intro_text".tr(),
                  style: AppTextStyles.font14Regular,
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }
}
