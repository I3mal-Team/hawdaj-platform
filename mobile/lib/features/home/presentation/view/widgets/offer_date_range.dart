import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class OfferDateRange extends StatelessWidget {
  final String startDate;
  final String endDate;
  final Color iconColorOne;
  final Color iconColorTwo;

  const OfferDateRange({
    super.key,
    required this.startDate,
    required this.endDate,
    required this.iconColorOne,
    required this.iconColorTwo,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        _buildDateItem(
          icon: AppAssets.calendarSvg,
          label: "end".tr(),
          date: endDate,
          iconColor: iconColorOne,
        ),
        _buildDateItem(
          icon: AppAssets.calendarSvg,
          label: "start".tr(),
          date: startDate,
          iconColor: iconColorTwo,
        ),
      ],
    );
  }

  Widget _buildDateItem({
    required String icon,
    required String label,
    required String date,
    required Color iconColor,
  }) {
    return Expanded(
      child: Row(
        children: [
          SvgPicture.asset(
            icon,
            colorFilter: ColorFilter.mode(iconColor, BlendMode.srcIn),
          ),
          WidthSpace(8.w),
          Text(
            "$label: $date",
            style: AppTextStyles.font12Bold.copyWith(color: AppColors.dark60),
          ),
        ],
      ),
    );
  }
}
