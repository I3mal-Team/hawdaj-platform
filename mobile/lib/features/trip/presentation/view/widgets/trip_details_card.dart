import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

import 'package:hawdaj/features/trip/presentation/view/widgets/trip_date_row.dart';

class TripDetailsCard extends StatelessWidget {
  const TripDetailsCard({
    super.key,

    required this.startDate,
    required this.endDate,
    required this.region1,
    required this.region2,
    required this.buttonOne,
    required this.buttonTwo,
    this.onButtonOnePressed,
    this.onButtonTwoPressed,
    required this.iconOnePath,
    required this.iconTwoPath,
    this.buttonOneColor, // NEW
    this.buttonTwoColor, // NEW
    this.buttonOneTextColor, // NEW
    this.buttonTwoTextColor,
    this.textColor,
    this.iconColor, // NEW
  });

  final String startDate;
  final String endDate;
  final String region1;
  final String region2;
  final String buttonOne;
  final String buttonTwo;
  final VoidCallback? onButtonOnePressed;
  final VoidCallback? onButtonTwoPressed;
  final String iconOnePath;
  final String iconTwoPath;
  final Color? textColor;
  final Color? iconColor;

  final Color? buttonOneColor; // NEW
  final Color? buttonTwoColor; // NEW
  final Color? buttonOneTextColor; // NEW
  final Color? buttonTwoTextColor; // NEW

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.all(16.w),
      child: Container(
        padding: EdgeInsets.all(12.w),
        decoration: BoxDecoration(
          color: Colors.grey.shade50,
          borderRadius: BorderRadius.circular(20.r),
          image: DecorationImage(
            image: AssetImage(AppAssets.untitled2),
            fit: BoxFit.cover,
          ),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'trip_details_title'.tr(),
              style: AppTextStyles.font20Bold.copyWith(color: Colors.black),
            ),
            HeightSpace(12.h),
            Row(
              children: [
                Expanded(
                  child: TripDateRow(
                    label: "trip_start_date_label".tr(),
                    subtitle: startDate,
                  ),
                ),
                WidthSpace(8.w),
                Expanded(
                  child: TripDateRow(
                    iconPath: AppAssets.location,
                    label: "start_region_label".tr(),
                    subtitle: region1,
                  ),
                ),
              ],
            ),
            HeightSpace(12.h),
            Row(
              children: [
                Expanded(
                  child: TripDateRow(
                    label: 'trip_end_date_label'.tr(),
                    subtitle: endDate,
                  ),
                ),
                Expanded(
                  child: TripDateRow(
                    iconPath: AppAssets.locationTick,
                    label: "end_region_label".tr(),
                    subtitle: region2,
                  ),
                ),
              ],
            ),
            //   HeightSpace(30.h),
            Row(
              children: [
                Expanded(
                  child: Padding(
                    padding: const EdgeInsets.symmetric(vertical: 8.0),
                    child: PrimaryButton(
                      padding: EdgeInsets.symmetric(vertical: 6.h),
                      //   iconPath: AppAssets.sendSquare,
                      iconPath: iconOnePath,
                      backgroundColor:
                          buttonOneColor ?? AppColors.primaryLight2,
                      textColor: buttonOneTextColor ?? AppColors.primary,
                      title: buttonOne,
                      onTap: onButtonOnePressed,
                    ),
                  ),
                ),
                WidthSpace(8.w),
                Expanded(
                  child: Padding(
                    padding: const EdgeInsets.symmetric(vertical: 8.0),
                    child: PrimaryButton(
                      padding: EdgeInsets.symmetric(vertical: 6.h),
                      //iconPath: AppAssets.trash,
                      iconPath: iconTwoPath,
                      backgroundColor: buttonTwoColor ?? Color(0xffF9E7E8),
                      textColor: buttonTwoTextColor ?? AppColors.redcolor,
                      title: buttonTwo,
                      onTap: onButtonTwoPressed,
                    ),
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
