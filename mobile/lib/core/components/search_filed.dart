import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import '../styles/app_colors.dart';
import '../styles/app_text_styles.dart';

class SearchFiled extends StatelessWidget {
  const SearchFiled({
    super.key,
    this.controller,
    this.hintText,
    this.onChanged,
    this.onTap,
    this.readOnly = false,
    this.onTapSuffix,
    this.showSuffix = true,
    this.isSuffixSearch = true,
    this.rawPopUpSuffix = const [],
    this.onSubmitted,
    this.keyboardType,
    this.suffix,
  });

  final TextEditingController? controller;
  final String? hintText;
  final void Function(String)? onChanged;
  final void Function()? onTap;
  final bool readOnly;
  final Function()? onTapSuffix;
  final Function(String)? onSubmitted;
  final bool? showSuffix;
  final bool isSuffixSearch;
  final Widget? suffix;
  final List<RawPopUpData> rawPopUpSuffix;
  final TextInputType? keyboardType;

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: ShapeDecoration(
        color: AppColors.white,
        shape: RoundedRectangleBorder(
          side: const BorderSide(width: 1, color: Color(0xFFF1F1F1)),
          borderRadius: BorderRadius.circular(16),
        ),
      ),
      child: TextField(
        readOnly: readOnly,
        onTap: onTap,
        onChanged: onChanged,
        onSubmitted: onSubmitted,
        maxLines: 1,
        controller: controller,
        keyboardType: keyboardType ?? TextInputType.text,
        cursorColor: AppColors.dark,
        style: AppTextStyles.font14Regular.copyWith(color: AppColors.uiBlack),
        textInputAction: TextInputAction.next,
        decoration: InputDecoration(
          hintText: hintText,
          hintStyle: AppTextStyles.font16Regular.copyWith(
            color: AppColors.lightGrey,
          ),
          prefixIcon: Padding(
            padding: const EdgeInsetsDirectional.only(start: 16, end: 8),
            child: SvgPicture.asset(AppAssets.searchNormal),
          ),
          prefixIconConstraints: const BoxConstraints(
            minWidth: 20,
            minHeight: 20,
          ),
          suffixIcon: suffix,
          isDense: true,
          filled: true,
          fillColor: AppColors.white,
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(16),
            borderSide: BorderSide.none,
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(16),
            borderSide: BorderSide.none,
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(16),
            // borderSide: BorderSide.none,
            borderSide: readOnly == true
                ? BorderSide.none
                : BorderSide(color: AppColors.primaryLight, width: 2.r),
          ),
          contentPadding: const EdgeInsets.symmetric(
            horizontal: 16,
            vertical: 13,
          ),
        ),
      ),
    );
  }
}
