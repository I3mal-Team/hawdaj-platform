import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class AppBarTopRowDetailsHorizontalIcon extends StatelessWidget {
  final VoidCallback? onSaveTap;
  final VoidCallback? onShareTap;
  final VoidCallback? onLocationTap;
  final bool isSaved;

  final bool showSave; // 👈 جديد
  final bool showShare; // 👈 جديد
  final bool showLocation; // 👈 جديد

  const AppBarTopRowDetailsHorizontalIcon({
    super.key,
    this.onSaveTap,
    this.onShareTap,
    this.onLocationTap,
    this.showSave = true, // 👈 افتراضي: يظهر
    this.showShare = true, // 👈 افتراضي: يظهر
    this.showLocation = true,
    required this.isSaved, // 👈 افتراضي: يظهر
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        if (showSave)
          GestureDetector(
            onTap: onSaveTap,
            child: SvgPicture.asset(
              isSaved ? AppAssets.activeSave : AppAssets.save,
              semanticsLabel: 'حفظ',
            ),
          ),
        if (showSave) HeightSpace(8.h),

        if (showShare)
          GestureDetector(
            onTap: onShareTap,
            child: SvgPicture.asset(AppAssets.share, semanticsLabel: 'مشاركة'),
          ),
        if (showShare) HeightSpace(8.h),

        if (showLocation)
          GestureDetector(
            onTap: onLocationTap,
            child: SvgPicture.asset(
              AppAssets.locationPin,
              semanticsLabel: 'الموقع',
            ),
          ),
      ],
    );
  }
}
