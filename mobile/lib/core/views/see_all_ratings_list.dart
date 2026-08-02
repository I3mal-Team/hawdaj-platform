import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/howdah_guides_section_items.dart';
import 'package:hawdaj/features/rates/presentation/view/share_your_rate_button.dart';
import 'package:hawdaj/features/tasneef/data/models/rating_model.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';

class SeeAllRatingsList extends StatelessWidget {
  const SeeAllRatingsList({
    super.key,
    required this.ratings,
    required this.type,
    required this.id,
  });
  final List<RatingModel> ratings;
  final String type;
  final String id;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        children: [
          TripStartAppBar(title: "reviews".tr()),
          if (ratings.isEmpty)
            const Expanded(child: Center(child: Text('لا يوجد تقييمات'))),
          Expanded(
            child: ListView.separated(
              itemCount: ratings.length,
              padding: const EdgeInsets.all(16),
              separatorBuilder: (_, __) => SizedBox(height: 12.h),
              itemBuilder: (context, index) {
                final rate = ratings[index];
                return HowdahGuidesSectionItems(
                  title: rate.name,
                  description: rate.rateText,
                  imageUrl: AppAssets.user,
                  rating: rate.rate.toString(),
                  colorTextDescription: Color(0xFF4B5565),
                  showBorder: true,
                  type: type,
                  parentId: id,
                  onTap: () {},
                );
              },
            ),
          ),

          Padding(
            padding: const EdgeInsets.all(8.0),
            child: ShareYourRateButton(
              type: type,
              parentId: id,
              width: MediaQuery.of(context).size.width,
            ),
          ),
        ],
      ),
    );
  }
}
