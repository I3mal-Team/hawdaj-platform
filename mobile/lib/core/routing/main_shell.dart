import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/core/utils/should_execute.dart';

class MainShell extends StatelessWidget {
  final StatefulNavigationShell navigationShell;

  const MainShell({super.key, required this.navigationShell});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: navigationShell,
      bottomNavigationBar: _buildCustomBottomBar(context),
    );
  }

  Widget _buildCustomBottomBar(BuildContext context) {
    final currentIndex = navigationShell.currentIndex;

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      child: Stack(
        clipBehavior: Clip.none,
        alignment: Alignment.topCenter,
        children: [
          // الخلفية
          Container(
            height: 65,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(30),
              border: Border.all(color: const Color(0xFFEEF2F6), width: 1),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.03),
                  blurRadius: 8,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: List.generate(5, (index) {
                if (index == 2) return const SizedBox(width: 40); // سنتر زر

                return _buildNavItem(
                  context: context,
                  index: index,
                  iconPath: _navIcon(index),
                  label: _navLabel(index),
                  isSelected: currentIndex == index,
                  onTap: () {
                    if (index == 4) {
                      shouldExecute(
                        context: context,
                        callback: () {
                          navigationShell.goBranch(
                            index,
                            initialLocation: index == currentIndex,
                          );
                        },
                      );
                    } else {
                      navigationShell.goBranch(
                        index,
                        initialLocation: index == currentIndex,
                      );
                    }
                  },
                );
              }),
            ),
          ),

          // زر السنتر
          Positioned(
            top: -20, // يعلو فوق البار
            child: GestureDetector(
              onTap: () {
                navigationShell.goBranch(2, initialLocation: true);
              },
              child: Container(
                width: 60,
                height: 60,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: AppColors.primary,
                  boxShadow: [
                    BoxShadow(
                      color: AppColors.primary.withOpacity(0.3),
                      blurRadius: 15,
                      spreadRadius: 1,
                      offset: const Offset(0, 6),
                    ),
                  ],
                ),
                child: Padding(
                  padding: const EdgeInsets.all(14.0),
                  child: SvgPicture.asset(AppAssets.send, color: Colors.white),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildNavItem({
    required int index,
    required String iconPath,
    required String label,
    required bool isSelected,
    required VoidCallback onTap,
    required BuildContext context,
  }) {
    return GestureDetector(
      onTap: onTap,
      child: SizedBox(
        width: 70,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            SvgPicture.asset(
              iconPath,
              width: 24,
              height: 24,
              color: isSelected ? AppColors.primary : Colors.grey,
            ),
            const SizedBox(height: 4),
            Text(
              label.tr(context: context),
              style: TextStyle(
                fontSize: 12,
                color: isSelected ? AppColors.primary : Colors.grey,
              ),
            ),
          ],
        ),
      ),
    );
  }

  String _navIcon(int index) {
    switch (index) {
      case 0:
        return AppAssets.home;
      case 1:
        return AppAssets.element;
      case 3:
        return AppAssets.explore;
      case 4:
        return AppAssets.profileSvg;
      default:
        return AppAssets.profile;
    }
  }

  String _navLabel(int index) {
    switch (index) {
      case 0:
        return 'home';
      case 1:
        return 'categories';
      case 3:
        return 'explore';
      case 4:
        return 'settings';
      default:
        return '';
    }
  }
}
