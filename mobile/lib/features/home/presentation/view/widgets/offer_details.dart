import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class OfferDetails extends StatelessWidget {
  const OfferDetails({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        _buildDetailItem(icon: AppAssets.menu, text: "لا توجد فئة طعام محدده"),
        _buildDetailItem(
          icon: AppAssets.location,
          text: "الرياض, الرياض",
          iconColor: Colors.grey,
        ),
      ],
    );
  }

  Widget _buildDetailItem({
    required String icon,
    required String text,
    Color? iconColor,
  }) {
    return Expanded(
      child: Row(
        children: [
          SvgPicture.asset(icon, color: iconColor),
          WidthSpace(8.w),
          Text(
            text,
            style: AppTextStyles.font12Bold.copyWith(color: AppColors.dark60),
          ),
        ],
      ),
    );
  }
}
