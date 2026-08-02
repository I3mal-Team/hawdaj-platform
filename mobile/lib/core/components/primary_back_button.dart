import 'package:hawdaj/core/components/primary_icon_button.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';

class PrimaryBackButton extends StatelessWidget {
  const PrimaryBackButton({super.key, this.onPressedBack});
  final Function()? onPressedBack;

  @override
  Widget build(BuildContext context) {
    return PrimaryIconButton(
      margin: EdgeInsetsDirectional.only(start: 24.w),
      icon: SvgPicture.asset(AppAssets.backIcon),
      onPressed: () => onPressedBack ?? GoRouter.of(context).pop(true),
    );
  }
}
