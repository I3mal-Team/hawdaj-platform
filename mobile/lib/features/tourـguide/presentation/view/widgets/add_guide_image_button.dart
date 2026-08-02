// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart'; // ← مهم: لعرض SVG
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class AddUserImageButton extends StatelessWidget {
  final File? pickedFile; // صورة محلية مختارة
  final VoidCallback? onPickImage; // دالة اختيار الصورة
  final String? avatarLink; // رابط الشبكة (من الموديل)

  const AddUserImageButton({
    super.key,
    required this.pickedFile,
    required this.onPickImage,
    this.avatarLink,
  });

  bool get _hasNetwork => (avatarLink != null && avatarLink!.trim().isNotEmpty);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onPickImage,
      child: Stack(
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(12), // نفس الديكور
            child: Container(
              //    color: AppColors.inactive4,
              width: 100.w,
              height: 100.w,
              child: _buildImage(),
            ),
          ),
          Positioned(
            bottom: 0,
            right: 2,
            child: Container(
              padding: EdgeInsets.all(5.r),
              width: 30.w,
              height: 30.w,
              decoration: BoxDecoration(
                color: AppColors.primary,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Image.asset(AppAssets.edit, color: Colors.white),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildImage() {
    // صورة محلية؟
    if (pickedFile != null) {
      return Image.file(
        pickedFile!,
        fit: BoxFit.cover,
        width: double.infinity,
        height: double.infinity,
      );
    }

    // رابط شبكة؟
    if (_hasNetwork) {
      return Image.network(
        avatarLink!,
        fit: BoxFit.cover,
        width: double.infinity,
        height: double.infinity,
        errorBuilder: (_, __, ___) => _fallbackAvatar(),
        // ممكن تضيف loadingBuilder لو حابب
      );
    }

    // افتراضي (SVG أو PNG)
    return _fallbackAvatar();
  }

  Widget _fallbackAvatar() {
    final path = AppAssets.user; // لو ده SVG لازم نعرضه بـ SvgPicture.asset
    if (path.toLowerCase().endsWith('.svg')) {
      return SvgPicture.asset(path, fit: BoxFit.contain, width: 57.44.w);
    }
    return Image.asset(path, fit: BoxFit.contain, width: 57.44.w);
  }
}
