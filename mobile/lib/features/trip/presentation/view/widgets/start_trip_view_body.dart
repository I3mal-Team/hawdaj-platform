import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class StartTripViewBody extends StatelessWidget {
  const StartTripViewBody({
    super.key,
    required this.title,
    required this.description,
    required this.onTap,
    required this.textbutton,
    required this.subtitle,
    this.backIconPath,
    this.onBackTap,
    this.allowBack = false,
  });
  final String title;
  final String description;
  final Function() onTap;
  final String textbutton;
  final String subtitle;
  final String? backIconPath;
  final Function()? onBackTap;
  final bool? allowBack;

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == TextDirection.rtl;

    return Stack(
      children: [
        Image.asset(
          AppAssets.onboarding(2),
          fit: BoxFit.cover,
          width: double.infinity,
          height: double.infinity,
        ),
        if (allowBack == true)
          Positioned(
            child: GestureDetector(
              onTap: onBackTap ?? () => Navigator.of(context).pop(),
              child: Padding(
                padding: const EdgeInsets.only(
                  left: 16.0,
                  top: 25.0,
                  right: 16.0,
                ),
                child: Transform(
                  alignment: Alignment.center,
                  transform: Matrix4.rotationY(isRtl ? 0 : 3.1416),
                  child: SvgPicture.asset(backIconPath ?? AppAssets.arrow),
                ),
              ),
            ),
          ),
        Align(
          alignment: Alignment.topCenter,
          child: Padding(
            padding: const EdgeInsets.only(left: 16.0, top: 25.0),
            child: Text(
              title,

              textAlign: TextAlign.center,
              style: AppTextStyles.font24Regular.copyWith(
                color: Colors.white,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ),
        Positioned(
          bottom: 100.h,
          left: 0,
          right: 0,
          child: Padding(
            padding: const EdgeInsets.all(8.0),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.start,

              children: [
                Text(
                  subtitle,

                  style: AppTextStyles.font40Bold.copyWith(color: Colors.white),
                ),
                Text(
                  '$description',
                  textAlign: TextAlign.center,
                  style: AppTextStyles.font14Regular.copyWith(
                    color: Colors.white,
                  ),
                ),
              ],
            ),
          ),
        ),
        Align(
          alignment: Alignment.bottomCenter,
          child: Padding(
            padding: const EdgeInsets.all(8.0),
            child: PrimaryButton(
              width: double.infinity,
              height: 50.h,
              title: textbutton,
              backgroundColor: Colors.white,
              textColor: Colors.black,
              onTap: onTap,
            ),
          ),
        ),
      ],
    );
  }
}
