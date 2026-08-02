import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

class CategoryCardItems extends StatelessWidget {
  const CategoryCardItems({
    super.key,
    required this.imagePath,
    required this.label,
  });
  final String imagePath;
  final String label;
  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(20),
      child: Stack(
        children: [
          Image.asset(
            imagePath,
            width: 123.w,
            height: 100.h,
            fit: BoxFit.cover,
          ),
          Positioned(
            bottom: 0,
            left: 0,
            right: 0,
            child: Container(
              padding: const EdgeInsets.symmetric(vertical: 10),
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [Colors.black.withOpacity(0.6), Colors.transparent],
                  begin: Alignment.bottomCenter,
                  end: Alignment.topCenter,
                ),
              ),
              alignment: Alignment.center,
              child: Text(
                label.tr(),
                style: AppTextStyles.font18Bold.copyWith(
                  color: Colors.white,

                  height: 1.20,
                ),
                textAlign: TextAlign.center,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
