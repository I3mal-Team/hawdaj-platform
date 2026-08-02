import 'package:flutter/material.dart';
import 'package:hawdaj/core/styles/app_colors.dart';

Color platformColor(String p) {
  switch (p) {
    case 'LinkedIn':
      return const Color(0xff0a66c2);
    case 'YouTube':
      return const Color(0xffff0000);
    case 'X':
      return Colors.black;
    case 'Instagram':
      return const Color(0xff8c008c);
    case 'TikTok':
      return Colors.black;
    case 'Personal Site':
      return AppColors.primary;
    default:
      return AppColors.primary;
  }
}
