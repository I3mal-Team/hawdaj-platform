import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_gradient_background.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_overlay_image.dart';

class TripStartAppBar extends StatelessWidget {
  final String title;
  final bool showBack; // ← إظهار أو إخفاء زر الرجوع
  final VoidCallback? onBack;
  final Widget? backIcon; // ← أيقونة الرجوع
  final Widget? extraIcon; // ← أيقونة إضافية (يسار)
  final VoidCallback? onExtraTap;
  final bool? showimage;

  const TripStartAppBar({
    super.key,
    required this.title,
    this.showBack = true,
    this.showimage = true,
    this.onBack,
    this.backIcon,
    this.extraIcon,
    this.onExtraTap,
  });

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 120.h,
      child: Stack(
        children: [
          //
          if (showimage ?? true)
            const AppBarOverlayImage(image: AppAssets.appbarImage),
          const AppBarGradientBackground(),

          Align(
            alignment: Alignment.bottomCenter,
            child: Container(
              height: 10.h,
              decoration: const BoxDecoration(
                boxShadow: [
                  BoxShadow(
                    color: Colors.white,
                    blurRadius: 4,
                    spreadRadius: -2,
                    offset: Offset(0, -1),
                  ),
                ],
              ),
            ),
          ),
          Center(
            child: Text(
              title,
              style: AppTextStyles.font24Regular.copyWith(
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
          if (showBack)
            Positioned(
              top: 40.h,
              right: 12.w,
              child: GestureDetector(
                onTap: onBack ?? () => Navigator.pop(context),
                child: backIcon ?? SvgPicture.asset(AppAssets.arrow),
              ),
            ),
          if (extraIcon != null)
            Positioned(
              top: 40.h,
              left: 12.w,
              child: GestureDetector(onTap: onExtraTap, child: extraIcon!),
            ),
        ],
      ),
    );
  }
}
