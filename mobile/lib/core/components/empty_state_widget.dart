import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';

class EmptyStateWidget extends StatelessWidget {
  const EmptyStateWidget({
    super.key,
    required this.message,
    this.subMessage,
    this.iconPath,
    this.isSvgIcon = true,
    this.showActionButton = false,
    this.actionLabel,
    this.onActionPressed,
    this.iconWidth,
    this.iconHeight,
    this.padding,
  });

  final String message;
  final String? subMessage;
  final String? iconPath;
  final bool isSvgIcon;
  final bool showActionButton;
  final String? actionLabel;
  final void Function()? onActionPressed;
  final double? iconWidth;
  final double? iconHeight;
  final EdgeInsetsGeometry? padding;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: IntrinsicWidth(
        child: Container(
          padding: padding,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              if (iconPath != null)
                Padding(
                  padding: const EdgeInsets.only(bottom: 16),
                  child: isSvgIcon
                      ? SvgPicture.asset(
                          iconPath!,
                          width: iconWidth,
                          height: iconHeight,
                          fit: BoxFit.cover,
                        )
                      : Image.asset(
                          iconPath!,
                          width: iconWidth,
                          height: iconHeight,
                          fit: BoxFit.cover,
                        ),
                ),
              Text(
                message,
                style: AppTextStyles.font16Regular,
                textAlign: TextAlign.center,
              ),
              if (subMessage != null)
                Padding(
                  padding: const EdgeInsets.only(top: 4),
                  child: Text(subMessage!, style: AppTextStyles.font12Regular),
                ),
              // if (showActionButton)
              // Padding(
              //   padding: const EdgeInsets.only(top: 8),
              //   child: PrimaryButton(
              //     onPressed: onActionPressed,
              //     label: actionLabel ?? '',
              //     padding: const EdgeInsets.all(6.5),
              //   ),
              // ),
            ],
          ),
        ),
      ),
    );
  }
}
