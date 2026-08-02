import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_gradient_background.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_overlay_image.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_search_bar.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_text_content.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_top_row.dart';

class CustomAppBarHome extends StatelessWidget {
  const CustomAppBarHome({super.key});

  @override
  Widget build(BuildContext context) {
    final topPadding = MediaQuery.of(context).padding.top;

    return SliverAppBar(
      expandedHeight: 240.h,
      pinned: true,
      backgroundColor: Colors.transparent,
      automaticallyImplyLeading: false,
      flexibleSpace: FlexibleSpaceBar(
        background: Stack(
          children: [
            const AppBarGradientBackground(),
            const AppBarOverlayImage(),
            Padding(
              padding: EdgeInsets.only(
                top: topPadding + 36.h,
                left: 16.w,
                right: 16.w,
              ),
              child: const AppBarTopRow(),
            ),
            Align(
              alignment: Alignment.center,
              child: Padding(
                padding: EdgeInsets.only(
                  top: topPadding + 20.h,
                  left: 16.w,
                  right: 16.w,
                ),
                child: const AppBarTextContent(),
              ),
            ),
            const Align(
              alignment: Alignment.bottomCenter,
              child: Padding(
                padding: EdgeInsets.only(bottom: 16),
                child: AppBarSearchBar(),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
