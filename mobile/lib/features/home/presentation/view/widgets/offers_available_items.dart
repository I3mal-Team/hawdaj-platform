import 'package:cached_network_image/cached_network_image.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/init/calculate_distance_to_place.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

import 'package:hawdaj/features/home/presentation/view/widgets/offer_date_range.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/offer_details.dart';
import 'package:hawdaj/features/home/presentation/view/widgets/offer_header.dart';
import 'package:hawdaj/features/restaurants/data/model/offer_item_model.dart';

class OffersAvailableItems extends StatelessWidget {
  const OffersAvailableItems({
    super.key,
    required this.offer,
    required this.destLat,
    required this.destLong,
  });
  final OfferItemModel offer;
  final double destLat;
  final double destLong;
  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        baseBottomSheet(
          context: context,
          title: "offer_details".tr(),
          hideNavBar: true,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              HeightSpace(16.h),
              CachedNetworkImage(imageUrl: offer.image, height: 200.h),
              HeightSpace(16.h),
              HeightSpace(12.h),
              OfferDateRange(
                startDate: offer.from,
                endDate: offer.to,
                iconColorOne: AppColors.grey,
                iconColorTwo: AppColors.grey,
              ),
              HeightSpace(12.h),
              const OfferDetails(),
              HeightSpace(16.h),
              Row(
                children: [
                  FutureBuilder<double>(
                    future: calculateDistanceToPlace(destLat, destLong),
                    builder: (context, snapshot) {
                      if (snapshot.connectionState == ConnectionState.waiting) {
                        return Padding(
                          padding: EdgeInsets.symmetric(vertical: 8.h),
                          child: Center(child: Text("loading".tr())),
                        );
                      } else if (snapshot.hasError) {
                        return Padding(
                          padding: EdgeInsets.symmetric(vertical: 8.h),
                          child: Center(
                            child: Text(
                              'distance_error'.tr(),
                              style: TextStyle(
                                color: Colors.red,
                                fontSize: 14.sp,
                              ),
                            ),
                          ),
                        );
                      } else {
                        final distance = snapshot.data ?? 0;
                        return Row(
                          mainAxisSize: MainAxisSize.min,
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisAlignment: MainAxisAlignment.start,
                          children: [
                            Image.asset(
                              AppAssets.mapIcon,
                              width: 24.w,
                              height: 24.h,
                            ),
                            SizedBox(width: 6.w),
                            Text(
                              'map_distance'.tr(
                                namedArgs: {
                                  'distance': distance.toStringAsFixed(2),
                                },
                              ),
                              style: TextStyle(
                                fontSize: 14.sp,
                                fontWeight: FontWeight.w500,
                                color: Colors.black87,
                              ),
                            ),
                          ],
                        );
                      }
                    },
                  ),
                ],
              ),
            ],
          ),
        );
      },
      child: Container(
        padding: EdgeInsets.all(12.h),
        clipBehavior: Clip.antiAlias,
        decoration: ShapeDecoration(
          gradient: const LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [Color(0xFFF2E1FF), Color(0xFFF9F9F9)],
          ),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            OfferHeader(offer: offer),
            HeightSpace(10.h),
            OfferDateRange(
              startDate: offer.from,
              endDate: offer.to,
              iconColorOne: AppColors.grey,
              iconColorTwo: AppColors.grey,
            ),
            HeightSpace(10.h),
            const OfferDetails(),
          ],
        ),
      ),
    );
  }
}
