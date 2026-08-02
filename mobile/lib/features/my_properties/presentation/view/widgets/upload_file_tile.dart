import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class UploadFileTile extends StatelessWidget {
  const UploadFileTile({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          width: 48.w,
          height: 48.w,
          decoration: ShapeDecoration(
            color: Colors.white,
            shape: RoundedRectangleBorder(
              side: const BorderSide(width: 1, color: Color(0xFFEEF2F6)),
              borderRadius: BorderRadius.circular(16),
            ),
          ),
          child: SvgPicture.asset(
            AppAssets.solarclouduploadoutline,
            width: 24,
            height: 24,
          ),
        ),
        SizedBox(width: 8.w),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('choose_file'.tr()),
              Text('upload_files_hint'.tr()),
            ],
          ),
        ),
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
          decoration: ShapeDecoration(
            shape: RoundedRectangleBorder(
              side: const BorderSide(width: 1, color: Color(0xFFEEF2F6)),
              borderRadius: BorderRadius.circular(8),
            ),
          ),
          child: Text(
            'browse_file'.tr(),
            style: TextStyle(
              color: Colors.black,
              fontSize: 14.sp,
              fontFamily: 'IBM Plex Sans Arabic',
              fontWeight: FontWeight.w400,
            ),
          ),
        ),
      ],
    );
  }
}
