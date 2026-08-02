import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:flutter/material.dart';

class ShowAllWidget extends StatelessWidget {
  final String title;
  final String showAllText;
  final VoidCallback onTap;
  final EdgeInsetsGeometry padding;
  final bool showAllButton;

  const ShowAllWidget({
    super.key,
    required this.title,
    required this.onTap,
    required this.showAllText,
    this.padding = EdgeInsets.zero,
    this.showAllButton = true,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: padding,
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(title, style: AppTextStyles.font16Bold),
          if (showAllButton)
            GestureDetector(
              onTap: onTap,
              child: Text(
                showAllText,
                style: AppTextStyles.font12Regular.copyWith(
                  color: AppColors.lightBlue,
                ),
              ),
            ),
        ],
      ),
    );
  }
}
