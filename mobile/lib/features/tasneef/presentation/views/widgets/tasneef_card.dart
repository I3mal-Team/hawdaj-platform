import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/app_fonts.dart';
import 'package:hawdaj/features/home/data/model/explore_category_model.dart';
import 'package:hawdaj/core/components/spaces.dart';

class TasneefCard extends StatelessWidget {
  const TasneefCard({super.key, required this.categoryModel});
  final ExploreCategoryModel categoryModel;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        String routeWithParams = categoryModel.routes;

        // Add query parameters for unified views
        if (categoryModel.routes == RoutesKeys.kTasneefPlacesListView) {
          routeWithParams =
              '${categoryModel.routes}?type=${categoryModel.type}&manor=${categoryModel.manor}&title=${Uri.encodeComponent(categoryModel.name)}';
        }

        push(routeWithParams, context);
      },
      child: Container(
        clipBehavior: Clip.hardEdge,
        decoration: BoxDecoration(borderRadius: BorderRadius.circular(12.r)),
        child: Stack(
          alignment: Alignment.bottomCenter,
          children: [
            Image.asset(
              categoryModel.imagePath,
              width: double.infinity,
              fit: BoxFit.cover,
              alignment: Alignment.center,
            ),
            Container(
              width: double.infinity,
              height: 36.h,
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [
                    Colors.black.withOpacity(0),
                    Colors.black.withOpacity(1),
                  ],
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                ),
              ),
            ),

            Column(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                Text(
                  categoryModel.name.tr(context: context),
                  textAlign: TextAlign.right,
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 18.sp,
                    fontFamily: AppFonts.theYearOfTheCamel,
                    fontWeight: FontWeight.w900,
                  ),
                ),
                HeightSpace(7.h),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
