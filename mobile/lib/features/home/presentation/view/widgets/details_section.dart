import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

import 'dart:ui' as ui;
import 'package:url_launcher/url_launcher.dart';


class DetailsSection extends StatelessWidget {
  final String? rating;
  final String address;
  final String description;
  final String title;
  final bool? button;

  const DetailsSection({
    super.key,
    this.rating,
    required this.address,
    required this.description,
    required this.title,
    this.button,
  });

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == ui.TextDirection.rtl;
    return SliverToBoxAdapter(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // 🔸 العنوان والتقييم
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: Text(
                  title,

                  style: AppTextStyles.font20Bold.copyWith(height: 1.2),
                ),
              ),
              if (rating != null)
                Row(
                  mainAxisSize: MainAxisSize.min,
                  textDirection: isRtl
                      ? ui.TextDirection.ltr
                      : ui.TextDirection.rtl,

                  children: [
                    SvgPicture.asset(AppAssets.star),
                    SizedBox(width: 4.w),
                    Text(
                      '($rating)',
                      style: AppTextStyles.font14Regular.copyWith(
                        color: AppColors.yellowColor,
                      ),
                    ),
                  ],
                ),

              if (button != null)
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 12,
                    vertical: 2,
                  ),
                  clipBehavior: Clip.antiAlias,
                  decoration: ShapeDecoration(
                    color: const Color(0xFFEEF2F6) /* Color-Neutrals-100 */,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(6),
                    ),
                  ),
                  child: Row(
                    children: [
                      CircleAvatar(
                        radius: 4.w,
                        backgroundColor: button!
                            ? Colors.red
                            : AppColors.successGreen,
                      ),
                      WidthSpace(4.w),
                      Text(
                        button! ? ' closed'.tr() : 'open'.tr(),
                        style: AppTextStyles.font12Regular.copyWith(
                          color: AppColors.obsidianBlack,
                        ),
                      ),
                    ],
                  ),
                ),
            ],
          ),
          HeightSpace(8.h),
          // 🔸 الموقع
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              SvgPicture.asset(AppAssets.location),
              SizedBox(width: 2.w),
              Expanded(
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    Expanded(
                      child: Text(
                        address,
                        style: AppTextStyles.font12Regular.copyWith(
                          color: AppColors.primary,
                        ),
                        overflow: TextOverflow.ellipsis,
                        maxLines: 1,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),

          HeightSpace(12.h),

          // 🔸 وصف المكان
          description.contains(RegExp(r'<[^>]*>'))
              ? Html(
                  data: description,
                  onLinkTap: (url, _, __) async {
                    if (url != null) {
                      final uri = Uri.parse(url);
                      if (await canLaunchUrl(uri)) {
                        await launchUrl(uri);
                      }
                    }
                  },
                  style: {
                    "body": Style(
                      textAlign: isRtl ? ui.TextAlign.right : ui.TextAlign.left,
                      fontSize: FontSize(12.sp),
                      color: const Color(0xFF4B5565),
                      lineHeight: LineHeight(1.5),
                      margin: Margins.zero,
                      padding: HtmlPaddings.zero,
                    ),
                    "p": Style(
                      margin: Margins.zero,
                      padding: HtmlPaddings.zero,
                    ),
                  },
                )
              : Text(
                  description,
                  textAlign: isRtl ? ui.TextAlign.right : ui.TextAlign.left,
                  style: AppTextStyles.font12Regular.copyWith(
                    color: const Color(0xFF4B5565),
                    height: 1.5,
                  ),
                ),

          HeightSpace(12.h),
        ],
      ),
    );
  }
}
