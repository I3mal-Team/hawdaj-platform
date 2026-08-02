import 'dart:io';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/seconday_button.dart';
import 'package:hawdaj/core/components/text_components/title_text.dart';
import 'package:hawdaj/core/components/text_components/subtitle_text.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/features/force_update/constants/app_store_links.dart';
import 'package:url_launcher/url_launcher.dart';

class ForceUpdateDialog extends StatefulWidget {
  final String currentVersion;
  final String latestVersion;
  final bool isMandatory;
  final VoidCallback? onRememberChoice;

  const ForceUpdateDialog({
    super.key,
    required this.currentVersion,
    required this.latestVersion,
    required this.isMandatory,
    this.onRememberChoice,
  });

  @override
  State<ForceUpdateDialog> createState() => _ForceUpdateDialogState();
}

class _ForceUpdateDialogState extends State<ForceUpdateDialog> {
  bool _rememberChoice = false;

  @override
  Widget build(BuildContext context) {
    return PopScope(
      canPop: !widget.isMandatory, // Prevent dismissal if mandatory
      child: Dialog(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16.r),
        ),
        child: Padding(
          padding: EdgeInsets.all(24.w),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // Icon
              Icon(Icons.system_update, size: 64.w, color: AppColors.primary),
              SizedBox(height: 16.h),

              // Title
              TitleText(
                widget.isMandatory
                    ? 'update_required'.tr()
                    : 'update_available'.tr(),
                textAlign: TextAlign.center,
              ),
              SizedBox(height: 12.h),

              // Description
              SubtitleText(
                widget.isMandatory
                    ? 'update_required_message'.tr()
                    : 'update_available_message'.tr(),
                textAlign: TextAlign.center,
                color: Colors.grey[600],
              ),
              SizedBox(height: 16.h),

              // Version info
              Container(
                padding: EdgeInsets.all(12.w),
                decoration: BoxDecoration(
                  color: Colors.grey[100],
                  borderRadius: BorderRadius.circular(8.r),
                ),
                child: Column(
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        SubtitleText(
                          'current_version'.tr(),
                          color: Colors.grey[600],
                        ),
                        SubtitleText(
                          widget.currentVersion.split('+')[0],
                          weight: FontWeight.bold,
                        ),
                      ],
                    ),
                    SizedBox(height: 8.h),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        SubtitleText(
                          'latest_version'.tr(),
                          color: Colors.grey[600],
                        ),
                        SubtitleText(
                          widget.latestVersion.split('+')[0],
                          weight: FontWeight.bold,
                          color: AppColors.primary,
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              SizedBox(height: 20.h),

              // Remember choice checkbox (only for non-mandatory updates)
              if (!widget.isMandatory)
                GestureDetector(
                  onTap: () {
                    setState(() {
                      _rememberChoice = !_rememberChoice;
                    });
                  },
                  child: Row(
                    children: [
                      Checkbox(
                        value: _rememberChoice,
                        onChanged: (value) {
                          setState(() {
                            _rememberChoice = value ?? false;
                          });
                        },
                        activeColor: AppColors.primary,
                      ),
                      Expanded(child: SubtitleText('remember_my_choice'.tr())),
                    ],
                  ),
                ),
              if (!widget.isMandatory) SizedBox(height: 16.h),

              // Update button
              PrimaryButton(
                onTap: () {
                  _openStore();
                },
                title: 'update_now'.tr(),
              ),

              // Later button (only for non-mandatory updates)
              if (!widget.isMandatory) ...[
                SizedBox(height: 12.h),
                SecondaryButton(
                  onTap: () {
                    if (_rememberChoice && widget.onRememberChoice != null) {
                      widget.onRememberChoice!();
                    }
                    Navigator.of(context).pop();
                  },
                  title: 'maybe_later'.tr(),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _openStore() async {
    String url;
    if (Platform.isAndroid) {
      url = AppStoreLinks.googlePlayStoreUrl;
    } else if (Platform.isIOS) {
      url = AppStoreLinks.appleAppStoreUrl;
    } else {
      return;
    }

    final uri = Uri.parse(url);
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    }
  }
}
