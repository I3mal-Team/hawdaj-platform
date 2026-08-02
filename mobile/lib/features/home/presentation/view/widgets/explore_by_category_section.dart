import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';

import 'package:hawdaj/features/home/data/model/explore_category_model.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/category_card_items.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/view_all_widget.dart';
import 'package:hawdaj/core/components/spaces.dart';

class ExploreByCategorySection extends StatelessWidget {
  const ExploreByCategorySection({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                "explore_by_category".tr(),
                style: AppTextStyles.font20Bold.copyWith(height: 1.2),
              ),

              ViewAllWidget(
                onTap: () {
                  push(RoutesKeys.kHawdajTasneef, context);
                },
              ),
            ],
          ),
        ),
        HeightSpace(12.h),
        SizedBox(
          height: 100.h,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: dummyCategories.length,
            separatorBuilder: (_, __) => SizedBox(width: 12.w),
            itemBuilder: (context, index) {
              final category = dummyCategories[index];
              return GestureDetector(
                onTap: () {
                  String routeWithParams = category.routes;
                  if (category.routes == RoutesKeys.kTasneefPlacesListView) {
                    routeWithParams =
                        '${category.routes}?type=${category.type}&manor=${category.manor}&title=${Uri.encodeComponent(category.name)}';
                  }

                  push(routeWithParams, context);
                },
                child: CategoryCardItems(
                  imagePath: category.imagePath,
                  label: category.name,
                ),
              );
            },
          ),
        ),
      ],
    );
  }
}
