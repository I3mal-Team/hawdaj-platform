import 'dart:io';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:url_launcher/url_launcher.dart';
import 'dart:ui' as ui;

class TasneefAppsItemCard extends StatelessWidget {
  final UnifiedPlaceModel app;

  const TasneefAppsItemCard({super.key, required this.app});

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return GestureDetector(
      onTap: () {
        baseBottomSheet(
          title: 'app_details'.tr(),
          child: Column(
            children: [
              Container(
                clipBehavior: Clip.hardEdge,
                margin: EdgeInsets.only(bottom: 12.h),
                width: double.infinity,
                height: 152.h,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(12.r),
                ),
                child: CachedNetworkImage(
                  imageUrl: app.fullImageUrl,
                  fit: BoxFit.cover,
                  width: double.infinity,
                  placeholder: (context, url) => Container(
                    color: Colors.grey[200],
                    child: Center(
                      child: CircularProgressIndicator(strokeWidth: 2),
                    ),
                  ),
                  errorWidget: (context, url, error) => Image.asset(
                    AppAssets.onboarding(1),
                    fit: BoxFit.cover,
                    width: double.infinity,
                  ),
                ),
              ),
              HeightSpace(8.h),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,

                children: [
                  Expanded(
                    child: Row(
                      textDirection: isRtl
                          ? ui.TextDirection.ltr
                          : ui.TextDirection.rtl,
                      children: [
                        Expanded(
                          child: Text(
                            app.title,
                            // textAlign: isRtl ? TextAlign.left : TextAlign.right,
                            style: TextStyle(
                              color: Colors.black,
                              fontSize: 16.sp,
                              fontFamily: AppFonts.theYearOfTheCamel,
                              fontWeight: FontWeight.w800,
                              //  height: .1,
                            ),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                      ],
                    ),
                  ),
                  Container(
                    padding: EdgeInsets.symmetric(
                      horizontal: 8.w,
                      vertical: 4.h,
                    ),
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(6.r),
                      color: Color(0xffFFFDE5),
                    ),
                    child: Image.asset(AppAssets.starPng, width: 14.w),
                  ),
                ],
              ),
              HeightSpace(8.h),
              Text(
                app.description,
                textAlign: isRtl ? TextAlign.right : TextAlign.left,
                style: AppTextStyles.font12Regular,
                maxLines: 8,
                overflow: TextOverflow.ellipsis,
              ),
              HeightSpace(21.h),
              Row(
                mainAxisAlignment: MainAxisAlignment.start,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'download_app_quick_tap'.tr(),
                    textAlign: TextAlign.right,
                    style: AppTextStyles.font14ExtraBold,

                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
              HeightSpace(12.h),
              Row(
                children: [
                  if (Platform.isAndroid &&
                      app.androidLink != null &&
                      app.androidLink!.isNotEmpty)
                    Expanded(
                      child: GestureDetector(
                        onTap: () => _launchURL(app.androidLink!),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            SvgPicture.asset(AppAssets.googleDownload),
                          ],
                        ),
                      ),
                    ),

                  if (Platform.isAndroid &&
                      app.iosLink != null &&
                      app.androidLink != null)
                    SizedBox(width: 8.w),

                  if (Platform.isIOS &&
                      app.iosLink != null &&
                      app.iosLink!.isNotEmpty)
                    Expanded(
                      child: GestureDetector(
                        onTap: () => _launchURL(app.iosLink!),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [SvgPicture.asset(AppAssets.appleDownload)],
                        ),
                      ),
                    ),
                ],
              ),
            ],
          ),
          context: context,
          hideNavBar: false,
        );
      },
      child: Container(
        padding: EdgeInsets.all(8.r),
        clipBehavior: Clip.hardEdge,
        margin: EdgeInsets.only(bottom: 12.h),
        width: double.infinity,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(12.r),
          color: Color(0xffF8FAFC),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Container(
              clipBehavior: Clip.hardEdge,
              margin: EdgeInsets.only(bottom: 12.h),
              width: double.infinity,
              height: 86.h,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(12.r),
              ),
              child: CachedNetworkImage(
                imageUrl: app.fullImageUrl,
                fit: BoxFit.cover,
                width: double.infinity,
                placeholder: (context, url) => Container(
                  color: Colors.grey[200],
                  child: Center(
                    child: CircularProgressIndicator(strokeWidth: 2),
                  ),
                ),
                errorWidget: (context, url, error) => Image.asset(
                  AppAssets.onboarding(1),
                  fit: BoxFit.cover,
                  width: double.infinity,
                ),
              ),
            ),
            HeightSpace(8.h),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,

              children: [
                Expanded(
                  child: Row(
                    textDirection: isRtl
                        ? ui.TextDirection.ltr
                        : ui.TextDirection.rtl,
                    children: [
                      Expanded(
                        child: Text(
                          app.title,
                          // textAlign: isRtl ? TextAlign.left : TextAlign.right,
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 16.sp,
                            fontFamily: AppFonts.theYearOfTheCamel,
                            fontWeight: FontWeight.w800,
                            //  height: .1,
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ],
                  ),
                ),
                Container(
                  padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 4.h),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(6.r),
                    color: Color(0xffFFFDE5),
                  ),
                  child: Image.asset(AppAssets.starPng, width: 14.w),
                ),
              ],
            ),
            HeightSpace(8.h),
            Text(
              app.description,
              textAlign: TextAlign.right,
              style: TextStyle(
                color: const Color(0xFF4B5565),
                fontSize: 12.sp,
                fontFamily: AppFonts.brandoArabic,
                fontWeight: FontWeight.w400,
              ),
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
            ),
            HeightSpace(10.h),
            Row(
              children: [
                if (app.androidLink != null) ...[
                  Expanded(
                    child: GestureDetector(
                      onTap: () => _launchURL(app.androidLink!),
                      child: Container(
                        padding: EdgeInsets.symmetric(vertical: 6.h),
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(6.r),
                          color: Color(0xffEEF2F6),
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            SvgPicture.asset(AppAssets.googlePlay, width: 20.w),
                          ],
                        ),
                      ),
                    ),
                  ),
                  if (app.iosLink != null) SizedBox(width: 8.w),
                ],
                if (app.iosLink != null) ...[
                  Expanded(
                    child: GestureDetector(
                      onTap: () => _launchURL(app.iosLink!),
                      child: Container(
                        padding: EdgeInsets.symmetric(vertical: 6.h),
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(6.r),
                          color: Color(0xffEEF2F6),
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            SvgPicture.asset(AppAssets.apple, width: 20.w),
                          ],
                        ),
                      ),
                    ),
                  ),
                ],
              ],
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _launchURL(String url) async {
    if (await canLaunchUrl(Uri.parse(url))) {
      await launchUrl(Uri.parse(url), mode: LaunchMode.externalApplication);
    } else {
      throw 'Could not launch $url';
    }
  }
}
