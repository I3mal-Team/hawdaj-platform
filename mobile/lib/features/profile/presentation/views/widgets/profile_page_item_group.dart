import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/profile_subtitle.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/setting_item.dart';

class ProfilePageItemGroup extends StatelessWidget {
  final String subtitle;
  final List<SettingItemData> items;
  final bool? show;

  const ProfilePageItemGroup({
    super.key,
    this.subtitle = 'الاعدادات',
    this.items = const [],
    this.show,
  });

  @override
  Widget build(BuildContext context) {
    return show ?? true
        ? Column(
            children: [
              ProfileSubtitle(title: subtitle),
              HeightSpace(16.h),
              Container(
                clipBehavior: Clip.hardEdge,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(8.r),
                ),
                child: Column(
                  children: items.isEmpty
                      ? List.generate(5, (index) => const SettingItem())
                      : items
                            .map(
                              (item) => SettingItem(
                                iconPath: item.iconPath,
                                title: item.title,
                                onTap: item.onTap,
                              ),
                            )
                            .toList(),
                ),
              ),
              HeightSpace(16.h),
            ],
          )
        : Container();
  }
}

class SettingItemData {
  final String iconPath;
  final String title;
  final VoidCallback? onTap;

  const SettingItemData({
    required this.iconPath,
    required this.title,
    this.onTap,
  });
}
