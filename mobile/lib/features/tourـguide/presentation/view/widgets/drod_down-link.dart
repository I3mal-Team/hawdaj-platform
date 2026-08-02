import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/components/spaces.dart';

class SocialMediaDropdown extends StatefulWidget {
  const SocialMediaDropdown({
    super.key,
    this.initialPlatform,
    this.initialLink,
    required this.onSave,
    this.onEdit,
    this.availablePlatforms,
  });

  final String? initialPlatform;
  final String? initialLink;
  final void Function(String platform, String link) onSave;
  final List<String>? availablePlatforms;
  final void Function(String oldPlatform, String newPlatform, String link)?
  onEdit;

  @override
  State<SocialMediaDropdown> createState() => _SocialMediaDropdownState();
}

class _SocialMediaDropdownState extends State<SocialMediaDropdown> {
  String? selectedSocialMedia;
  final formKey = GlobalKey<FormState>();
  final TextEditingController _controller = TextEditingController();

  IconData _platformIcon(String p) {
    switch (p) {
      case 'LinkedIn':
        return FontAwesomeIcons.linkedin;
      case 'youtube':
        return FontAwesomeIcons.youtube;
      case 'X':
        return FontAwesomeIcons.xTwitter;
      case 'Instagram':
        return FontAwesomeIcons.instagram;
      case 'TikTok':
        return FontAwesomeIcons.tiktok;
      case 'Personal Site':
        return FontAwesomeIcons.user;
      default:
        return Icons.link;
    }
  }

  Color _platformColor(String p) {
    switch (p) {
      case 'LinkedIn':
        return Colors.blueAccent;
      case 'youtube':
        return Colors.red;
      case 'X':
        return Colors.black;
      case 'Instagram':
        return Colors.purple;
      case 'TikTok':
        return Colors.black;
      case 'Personal Site':
        return AppColors.primary;
      default:
        return AppColors.primary;
    }
  }

  @override
  void initState() {
    super.initState();
    selectedSocialMedia = widget.initialPlatform;
    _controller.text = widget.initialLink ?? '';
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  String _normalize(String v) {
    final s = v.trim();

    // استثناء واتساب من التطبيع
    if (selectedSocialMedia?.toLowerCase() == 'whatsapp') {
      return s;
    }

    if (s.startsWith('http://') || s.startsWith('https://')) return s;
    return 'https://$s';
  }

  @override
  Widget build(BuildContext context) {
    return Form(
      key: formKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'choose_platform_and_add_link'.tr(),
            style: AppTextStyles.font14Regular.copyWith(
              color: AppColors.dark60,
            ),
          ),
          HeightSpace(16.h),
          Text('platform_label'.tr(), style: AppTextStyles.font14Regular),
          HeightSpace(4.h),
          Container(
            height: 60.h,
            width: MediaQuery.of(context).size.width,
            decoration: ShapeDecoration(
              color: Colors.white,
              shape: RoundedRectangleBorder(
                side: const BorderSide(width: 1, color: Color(0xFFEEF2F6)),
                borderRadius: BorderRadius.circular(12),
              ),
            ),
            child: PopupMenuButton<String>(
              position: PopupMenuPosition.under,
              child: ListTile(
                title: Row(
                  children: [
                    Image.asset(
                      AppAssets.monitorMobbile,
                      width: 24.w,
                      height: 24.h,
                    ),
                    const SizedBox(width: 10),
                    if (selectedSocialMedia != null) ...[
                      Icon(
                        _platformIcon(selectedSocialMedia!),
                        color: _platformColor(selectedSocialMedia!),
                      ),
                      const SizedBox(width: 10),
                    ],
                    Text(
                      selectedSocialMedia ?? 'choose'.tr(),
                      style: AppTextStyles.font14Regular.copyWith(
                        fontWeight: FontWeight.bold,
                        color: Colors.grey,
                      ),
                    ),
                  ],
                ),
                trailing: const Icon(Icons.arrow_drop_down),
              ),
              onSelected: (String value) {
                setState(() {
                  selectedSocialMedia = value;
                  _controller.clear();
                });
              },
              itemBuilder: (BuildContext context) => _buildMenuItems(),
            ),
          ),

          HeightSpace(16.h),
          Text('link_label'.tr(), style: AppTextStyles.font14Regular),
          HeightSpace(12.h),
          CustomTextField(
            controller: _controller,
            enabled: selectedSocialMedia != null,
            leadingIconPath: AppAssets.link,
            hint: 'enter_link_hint'.tr(),
          ),

          HeightSpace(20.h),

          Row(
            children: [
              Expanded(
                child: PrimaryButton(
                  height: 44,
                  padding: EdgeInsets.zero,
                  title: widget.initialPlatform == null
                      ? 'save'.tr()
                      : 'update'.tr(),
                  onTap: () {
                    if (!(formKey.currentState?.validate() ?? false)) return;

                    final platformNow =
                        selectedSocialMedia ?? widget.initialPlatform;
                    if (platformNow == null) {
                      showCustomFailureToast('choose_platform'.tr());
                      return;
                    }

                    final normalized = _normalize(_controller.text);

                    if (widget.initialPlatform != null) {
                      widget.onEdit?.call(
                        widget.initialPlatform!,
                        platformNow,
                        normalized,
                      );
                    } else {
                      widget.onSave(platformNow, normalized);
                    }

                    Navigator.of(context).pop(true);
                  },
                ),
              ),
              WidthSpace(12.w),
              Expanded(
                child: PrimaryButton(
                  height: 44,
                  backgroundColor: const Color(0xffF5F7F8),
                  textColor: AppColors.uiBlack,
                  padding: EdgeInsets.zero,
                  title: 'close'.tr(),
                  onTap: () => Navigator.of(context).pop(false),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  List<PopupMenuEntry<String>> _buildMenuItems() {
    final List<String> names =
        widget.availablePlatforms ??
        ['LinkedIn', 'youtube', 'X', 'Instagram', 'TikTok', 'Personal Site'];

    return names.map((name) {
      final isSelected = selectedSocialMedia == name;
      return PopupMenuItem<String>(
        value: name,
        child: Container(
          width: MediaQuery.of(context).size.width,
          height: 40,
          color: isSelected
              ? AppColors.grey.withOpacity(0.6)
              : Colors.transparent,
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Icon(_platformIcon(name), color: _platformColor(name)),
              const SizedBox(width: 10),
              Text(
                name,
                style: TextStyle(
                  color: isSelected ? Colors.white : Colors.black,
                ),
              ),
            ],
          ),
        ),
      );
    }).toList();
  }
}
