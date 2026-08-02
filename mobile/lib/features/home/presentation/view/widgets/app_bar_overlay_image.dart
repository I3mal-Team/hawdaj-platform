import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class AppBarOverlayImage extends StatelessWidget {
  const AppBarOverlayImage({super.key, this.image});
  final String? image;
  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: BorderRadius.vertical(bottom: Radius.circular(8.r)),
      child: Opacity(
        opacity: 0.8,
        child: Image.asset(
          image ?? AppAssets.homeBar,
          width: double.infinity,
          height: double.infinity,
          fit: BoxFit.cover,
        ),
      ),
    );
  }
}
