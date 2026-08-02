import 'package:flutter/material.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart' show RatingBar;
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class RateAppBottomSheet extends StatelessWidget {
  const RateAppBottomSheet({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(
        left: 16.w,
        right: 16.w,
        bottom: MediaQuery.of(context).viewInsets.bottom + 16.h,
        top: 8.h,
      ),
      child: SingleChildScrollView(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            HeightSpace(8.h),
            Image.asset(AppAssets.capa, width: 80.w, height: 80.h),
            HeightSpace(16.h),
            Text(
              "كيف هي تجربتك حتى الآن؟",
              style: AppTextStyles.font16Medium.copyWith(
                color: AppColors.obsidianBlack,
              ),
            ),
            HeightSpace(8.h),
            Text(
              "نحن نحب أن نعرف! تقييمك لتطبيقنا.",
              textAlign: TextAlign.center,
              style: AppTextStyles.font14Regular.copyWith(
                color: AppColors.grey,
              ),
            ),
            HeightSpace(24.h),

            RatingBar.builder(
              initialRating: 2,
              minRating: 1,
              allowHalfRating: false,
              itemCount: 5,
              itemPadding: const EdgeInsets.symmetric(horizontal: 6),
              itemBuilder: (context, _) => SvgPicture.asset(AppAssets.star),
              onRatingUpdate: (rating) {
                debugPrint(rating.toString());
              },
            ),

            HeightSpace(20.h),

            // if (!isUserLoggedIn) ...[
            //   CustomTextField(hint: "الاسم", controller: nameCtrl),
            //   HeightSpace(16.h),
            //   CustomTextField(
            //     hint: "البريد الإلكتروني",

            //     inputType: TextInputType.emailAddress,
            //   ),
            //   HeightSpace(16.h),
            // ],
            HeightSpace(16.h),
            CustomTextField(
              hint: "ابدأ بالكتابة هنا...",
              maxLines: 6,
              controller: TextEditingController(),
            ),

            HeightSpace(20.h),

            Row(
              children: [
                Expanded(
                  child: PrimaryButton(
                    title: 'ارسال',
                    onTap: () async {},
                    active: true,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
