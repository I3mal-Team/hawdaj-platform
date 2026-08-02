import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';

class SettingItem extends StatelessWidget {
  final String iconPath;
  final String title;
  final VoidCallback? onTap;

  const SettingItem({
    super.key,
    this.iconPath = AppAssets.tagUser,
    this.title = 'ملف المرشد السياحي',
    this.onTap,
  });

  bool get isSvg => iconPath.toLowerCase().endsWith('.svg');

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == TextDirection.rtl;
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: double.infinity,
        padding: EdgeInsets.all(8.r),
        decoration: const BoxDecoration(color: Color(0xffF8FAFC)),
        child: Row(
          children: [
            isSvg
                ? SvgPicture.asset(
                    iconPath,
                    width: 16.w,
                    color: AppColors.primary,
                  )
                : Image.asset(iconPath, width: 16.w, color: AppColors.primary),
            WidthSpace(8.w),
            Text(
              title,
              style: TextStyle(
                color: Colors.black,
                fontSize: 14.sp,
                fontFamily: AppFonts.theYearOfTheCamel,
                fontWeight: FontWeight.w800,
              ),
            ),
            const Spacer(),
            Transform(
              alignment: Alignment.center,
              transform: Matrix4.rotationY(isRtl ? 0 : 3.1416),
              child: Image.asset(
                AppAssets.arrowEnter,
                width: 14.w,
                // colorFilter: ColorFilter.mode(AppColors.primary, BlendMode.srcIn),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
