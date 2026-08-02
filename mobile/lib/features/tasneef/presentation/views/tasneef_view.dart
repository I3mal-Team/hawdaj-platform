import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/home/data/model/explore_category_model.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_gradient_background.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_overlay_image.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/app_bar_top_row.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_card.dart';

class TasneefView extends StatelessWidget {
  const TasneefView({super.key});

  @override
  Widget build(BuildContext context) {
    final topPadding = MediaQuery.of(context).padding.top;

    return Scaffold(
      backgroundColor: Colors.white,
      body: CustomScrollView(
        slivers: [
          SliverToBoxAdapter(
            child: SizedBox(
              height: 132.h,
              child: Stack(
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
                ],
              ),
            ),
          ),

          SliverToBoxAdapter(
            child: Padding(
              padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 12.h),
              child: Row(
                children: [
                  Text(
                    'howdaj_categories'.tr(context: context),
                    textAlign: TextAlign.right,
                    style: TextStyle(
                      color: Colors.black,
                      fontSize: 20.sp,
                      fontFamily: AppFonts.theYearOfTheCamel,
                      fontWeight: FontWeight.w900,
                    ),
                  ),
                ],
              ),
            ),
          ),

          // ✅ الشبكة
          SliverPadding(
            padding: EdgeInsets.symmetric(horizontal: 16.w),
            sliver: SliverGrid(
              delegate: SliverChildBuilderDelegate((context, index) {
                final category = dummyCategories[index];
                return TasneefCard(categoryModel: category);
              }, childCount: dummyCategories.length),
              gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                mainAxisSpacing: 16.h,
                crossAxisSpacing: 12.w,
                childAspectRatio: 1.0,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
