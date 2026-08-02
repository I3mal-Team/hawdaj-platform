import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class RatingWidget extends StatelessWidget {
  final String rating;
  final String? starIconPath;

  final double iconSize;
  final double spacing;

  const RatingWidget({
    Key? key,
    required this.rating,
    this.starIconPath,

    this.iconSize = 12,
    this.spacing = 4,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == TextDirection.rtl;
    return Row(
      mainAxisSize: MainAxisSize.min,
      textDirection: isRtl ? TextDirection.ltr : TextDirection.rtl,
      children: [
        SvgPicture.asset(
          starIconPath ?? AppAssets.star,
          width: iconSize.w,
          height: iconSize.h,
        ),
        SizedBox(width: spacing.w),
        Text(
          '($rating)',
          style: TextStyle(fontSize: 10.sp, color: AppColors.yellowColor),
        ),
      ],
    );
  }
}
