import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/features/home/data/model/places_model/place_response.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/category_daily_suggestions_items.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/head_title_section.dart';

class DailySuggestionsSection extends StatelessWidget {
  const DailySuggestionsSection({super.key, required this.place});
  final PlaceResponse place;
  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        HeadTitleSection(title: "daily_suggestions".tr(), showViewAll: false),
        HeightSpace(12.h),
        SizedBox(
          height: 185.h,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: place.items.length,
            separatorBuilder: (_, __) => SizedBox(width: 12.w),
            itemBuilder: (context, index) {
              final placeItem = place.items[index];
              return GestureDetector(
                onTap: () {
                  push(
                    RoutesKeys.kPlacesDetailsItems,
                    context,
                    extra: placeItem.slug,
                  );
                },
                child: CategoryDailySuggestionsItems(
                  isFavorite: placeItem.isFavorite,
                  imagePath: placeItem.image,
                  label: placeItem.title,
                  description: placeItem.description,
                  rating: placeItem.rate,
                  favoriteId: placeItem.id,
                  favoriteType: placeItem.type,

                  showDecoration: true,
                ),
              );
            },
          ),
        ),
      ],
    );
  }
}
