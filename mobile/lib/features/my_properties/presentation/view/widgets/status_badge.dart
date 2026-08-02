// import 'package:flutter/material.dart';
// import 'package:flutter_screenutil/flutter_screenutil.dart';
// import 'package:flutter_svg/svg.dart';
// import 'package:hawdaj/core/components/empty_state_widget.dart';
// import 'package:hawdaj/core/components/primary_button.dart';
// import 'package:hawdaj/core/routing/route_utils.dart';
// import 'package:hawdaj/core/routing/routes_keys.dart';
// import 'package:hawdaj/core/styles/app_text_styles.dart';
// import 'package:hawdaj/core/utils/app_assets.dart';
// import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_gradient_background.dart';
// import 'dart:ui' as ui;

import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class StatusBadge extends StatelessWidget {
  final String? status;

  const StatusBadge({super.key, this.status});

  @override
  Widget build(BuildContext context) {
    final normalized = (status ?? '').toLowerCase();

    // 🟢 إعداد الألوان حسب الحالة
    Color bgColor;
    Color textColor;
    String text;

    switch (normalized) {
      case 'accepted':
        bgColor = const Color(0xFFE6F4EA); // أخضر فاتح
        textColor = const Color(0xFF0F9D58); // أخضر غامق
        text = 'accepted'.tr(context: context); // ✅ ترجم حسب اللغة
        break;

      case 'pending':
        bgColor = const Color(0xFFFFF8E1); // أصفر فاتح
        textColor = const Color(0xFFF9A825); // أصفر غامق
        text = "pending".tr(context: context); // ⏳ ترجم حسب اللغة
        break;

      case 'rejected':
        bgColor = const Color(0xFFFFEBEE); // أحمر فاتح
        textColor = const Color(0xFFD32F2F); // أحمر غامق
        text = 'rejected'.tr(context: context); // ❌ ترجم حسب اللغة
        break;

      default:
        bgColor = Colors.grey.shade200;
        textColor = Colors.grey.shade600;
        text = tr('unknown');
    }

    return Container(
      padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 6.h),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8.r),
      ),
      child: Text(
        text,
        style: TextStyle(
          color: textColor,
          fontSize: 12.sp,
          fontFamily: 'IBM Plex Sans Arabic',
          fontWeight: FontWeight.w500,
        ),
      ),
    );
  }
}
