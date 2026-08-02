import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';

class IconTitleWidget extends StatelessWidget {
  const IconTitleWidget({
    super.key,
    required this.title,
    required this.iconPath,
  });

  final String title;
  final String iconPath;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        SvgPicture.asset(iconPath),
        WidthSpace(4.w),
        Text(
          title,
          style: AppTextStyles.font14Medium.copyWith(
            color: AppColors.inactiveText1,
          ),
        ),
      ],
    );
  }
}
