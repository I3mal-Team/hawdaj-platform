import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart'; // ✅
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class BottomSheetTripDealt extends StatelessWidget {
  const BottomSheetTripDealt({
    super.key,
    required this.title,
    required this.message,
    required this.onTapConfirm,
    required this.onTapCancel,
    required this.confirm,
    required this.cancel,
    this.image,
  });

  final String title;
  final String message;
  final VoidCallback onTapConfirm;
  final VoidCallback onTapCancel;
  final String confirm;
  final String cancel;
  final String? image;

  @override
  Widget build(BuildContext context) {
    return Builder(
      builder: (sheetCtx) {
        return SingleChildScrollView(
          padding: EdgeInsets.only(
            bottom: MediaQuery.of(sheetCtx).viewInsets.bottom,
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Center(child: _buildImage(image ?? AppAssets.close1)),
              HeightSpace(16.h),
              Text(
                title,
                style: AppTextStyles.font18Bold.copyWith(
                  color: AppColors.obsidianBlack,
                ),
              ),
              HeightSpace(16.h),
              Text(
                message,
                textAlign: TextAlign.center,
                maxLines: 3,
                style: AppTextStyles.font14Regular.copyWith(
                  color: AppColors.dark60,
                ),
              ),
              HeightSpace(16.h),
              Row(
                children: [
                  Expanded(
                    child: PrimaryButton(title: cancel, onTap: onTapCancel),
                  ),
                  WidthSpace(8.w),
                  Expanded(
                    child: PrimaryButton(
                      backgroundColor: AppColors.dangerLight1,
                      title: confirm,
                      textColor: AppColors.redcolor,
                      onTap: onTapConfirm,
                    ),
                  ),
                ],
              ),
              HeightSpace(16.h),
            ],
          ),
        );
      },
    );
  }

  /// ✅ يحدد لو الصورة SVG أو PNG/JPG
  Widget _buildImage(String path) {
    if (path.toLowerCase().endsWith('.svg')) {
      return SvgPicture.asset(
        path,
        width: 120.w,
        height: 120.h,
        fit: BoxFit.contain,
      );
    } else {
      return Image.asset(path, width: 120.w, height: 120.h, fit: BoxFit.cover);
    }
  }
}
