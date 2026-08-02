import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class CustomBackButton extends StatelessWidget {
  const CustomBackButton({super.key});

  @override
  Widget build(BuildContext context) {
    final isRtl = Directionality.of(context) == TextDirection.rtl;
    return GestureDetector(
      onTap: () => Navigator.pop(context),
      child: Transform(
        alignment: Alignment.center,
        transform: Matrix4.rotationY(isRtl ? 0 : 3.1416),
        child: SvgPicture.asset(AppAssets.arrow),
      ),
    );
  }
}
