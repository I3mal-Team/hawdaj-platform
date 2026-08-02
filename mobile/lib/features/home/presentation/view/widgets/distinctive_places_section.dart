import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/features/home/data/model/places_model/place_response.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/category_daily_suggestions_items.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/head_title_section.dart';

class DistinctivePlacesSection extends StatelessWidget {
  const DistinctivePlacesSection({super.key, required this.placeResponse});
  final PlaceResponse placeResponse;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        HeadTitleSection(
          title: 'featured_places'.tr(),
          onTap: () {
            push(RoutesKeys.kTasneefPlacesListView, context);
          },
        ),
        HeightSpace(12.h),
        SizedBox(
          height: 185.h,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: placeResponse.items.length,
            separatorBuilder: (_, __) => SizedBox(width: 12.w),
            itemBuilder: (context, index) {
              return GestureDetector(
                onTap: () {
                  push(
                    RoutesKeys.kPlacesDetailsItems,
                    context,
                    extra: placeResponse.items[index].slug,
                  );
                },
                child: CategoryDailySuggestionsItems(
                  isFavorite: placeResponse.items[index].isFavorite,
                  imagePath: placeResponse.items[index].image,
                  label: placeResponse.items[index].title,
                  description: placeResponse.items[index].description,
                  rating: placeResponse.items[index].rate,
                  favoriteId: placeResponse.items[index].id,
                  favoriteType: placeResponse.items[index].type,
                  // showDecoration: false,
                ),
              );
            },
          ),
        ),
      ],
    );
  }
}
