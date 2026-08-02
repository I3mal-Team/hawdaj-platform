import 'package:easy_localization/easy_localization.dart';
import 'package:hawdaj/core/components/global_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';

class CustomFailureWidget extends StatelessWidget {
  const CustomFailureWidget({
    super.key,
    required this.title,
    this.subTitle,
    this.image,
    this.isSvg = true,
    this.onRetry,
  });
  final String title;
  final String? subTitle;
  final String? image;
  final bool isSvg;
  final Function()? onRetry;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            if (image != null)
              Padding(
                padding: const EdgeInsets.only(bottom: 16),
                child: isSvg ? SvgPicture.asset(image!) : GNImage(image!),
              ),
            Text(title),
            if (subTitle != null)
              Padding(
                padding: const EdgeInsets.only(top: 8),
                child: Text(subTitle!),
              ),
            if (onRetry != null)
              Padding(
                padding: const EdgeInsets.only(top: 16),
                child: ElevatedButton.icon(
                  onPressed: onRetry,
                  label: Text("retry".tr()),
                  icon: const Icon(Icons.refresh),
                ),
              ),
          ],
        ),
      ),
    );
  }
}
