import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class InfoHeader extends StatelessWidget {
  const InfoHeader({
    super.key,
    required this.title,
    this.subtitle,
    required this.image,
    this.child,
  });
  final String title;
  final String? subtitle;
  final String image;
  final Widget? child;
  @override
  Widget build(BuildContext context) {
    return SliverPadding(
      padding: EdgeInsets.zero,
      sliver: SliverList(
        delegate: SliverChildListDelegate([
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                title,
                style: AppTextStyles.font20Bold.copyWith(height: 1.2),
              ),
              child != null ? child! : SizedBox(),
            ],
          ),
          subtitle != null
              ? Row(
                  children: [
                    SvgPicture.asset(
                      image,
                      color: AppColors.primary,
                      colorFilter: ColorFilter.mode(
                        AppColors.primary,
                        BlendMode.srcIn,
                      ),
                    ),
                    SizedBox(width: 2.w),
                    Text(
                      subtitle!,
                      style: AppTextStyles.font12Regular.copyWith(
                        color: AppColors.primary,
                      ),
                    ),
                  ],
                )
              : const SizedBox.shrink(),
          HeightSpace(12.h),
        ]),
      ),
    );
  }
}
