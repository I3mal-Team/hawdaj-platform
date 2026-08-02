import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class PropertyItemCard extends StatelessWidget {
  final String icon;
  final String title;
  final String value;

  const PropertyItemCard({
    super.key,
    required this.icon,
    required this.title,
    required this.value,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border.all(color: const Color(0xFFEEF2F6)),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              SvgPicture.asset(icon),
              SizedBox(width: 4.w),
              Text(
                title,
                style: AppTextStyles.font14Bold.copyWith(
                  color: const Color(0xFF4B5565),
                ),
              ),
            ],
          ),
          HeightSpace(8.h),
          Text(
            value,
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            style: AppTextStyles.font12Regular.copyWith(
              color: const Color(0xFF4B5565),
            ),
          ),
        ],
      ),
    );
  }
}
