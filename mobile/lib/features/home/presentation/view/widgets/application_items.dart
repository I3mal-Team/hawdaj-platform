import 'dart:io';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/functions/open_file_link.dart';
import 'package:hawdaj/features/home/data/model/application_model/application_model.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/application_items_button.dart';

class ApplicationItems extends StatelessWidget {
  const ApplicationItems({super.key, required this.application});
  final ApplicationModel application;

  @override
  Widget build(BuildContext context) {
    return Card(
      color: AppColors.lightRed2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20.r)),
      elevation: 0,
      margin: EdgeInsets.zero,
      child: Padding(
        padding: EdgeInsets.all(12.w),
        child: Column(
          children: [
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: 52,
                  height: 52,
                  decoration: ShapeDecoration(
                    image: DecorationImage(
                      image: CachedNetworkImageProvider(application.image),
                      fit: BoxFit.cover,
                    ),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                ),
                SizedBox(width: 10.w),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Expanded(
                            child: Text(
                              application.title,
                              style: AppTextStyles.font16Bold.copyWith(
                                color: AppColors.obsidianBlack,
                              ),
                              overflow: TextOverflow.ellipsis,
                            ),
                          ),
                          // SizedBox(width: 8.w),
                          // Container(
                          //   padding: const EdgeInsets.symmetric(
                          //     horizontal: 8,
                          //     vertical: 4,
                          //   ),
                          //   decoration: ShapeDecoration(
                          //     color: const Color(0xFFFEFCE8),
                          //     shape: RoundedRectangleBorder(
                          //       side: const BorderSide(
                          //         width: 1,
                          //         color: Color(0xFFFEEFC6),
                          //       ),
                          //       borderRadius: BorderRadius.circular(6),
                          //     ),
                          //   ),
                          //   child: Row(
                          //     mainAxisSize: MainAxisSize.min,
                          //     children: [
                          //       SvgPicture.asset(
                          //         AppAssets.star,
                          //         width: 12.w,
                          //         height: 12.h,
                          //       ),
                          //       SizedBox(width: 4.w),
                          //       const Text(
                          //         'مميز',
                          //         style: TextStyle(
                          //           color: Color(0xFFFFAF44),
                          //           fontSize: 10,
                          //           fontFamily: 'Brando Arabic',
                          //           fontWeight: FontWeight.w400,
                          //           height: 1.4,
                          //         ),
                          //       ),
                          //     ],
                          //   ),
                          // ),
                        ],
                      ),
                      SizedBox(height: 4.h),
                      Text(
                        application.description,
                        maxLines: 3,
                        overflow: TextOverflow.ellipsis,
                        textAlign: TextAlign.start,
                        style: AppTextStyles.font14SemiBold.copyWith(
                          color: AppColors.inactiveText1,
                          height: 1.50,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
            SizedBox(height: 10.h),
            Row(
              children: [
                if (Platform.isIOS)
                  Expanded(
                    child: ApplicationItemsButton(
                      text: "App Store",
                      onTap: () {
                        openFileLink(application.iosLink ?? "");
                      },
                      image: AppAssets.apple,
                    ),
                  ),
                if (Platform.isAndroid)
                  Expanded(
                    child: ApplicationItemsButton(
                      text: "Google Play",
                      onTap: () {
                        openFileLink(application.androidLink ?? "");
                      },
                      image: AppAssets.googlePlay,
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
