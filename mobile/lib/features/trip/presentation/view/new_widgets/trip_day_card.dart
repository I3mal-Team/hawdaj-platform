import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/presentation/view/new_widgets/day_header_card.dart';
import 'package:hawdaj/features/trip/presentation/view/new_widgets/period_section.dart';

class TripDayCard extends StatelessWidget {
  const TripDayCard({
    super.key,
    required this.model,
    required this.dayIndex,
    required this.showDelete,
  });
  final EnhancedDay model;
  final int dayIndex;
  final bool showDelete;
  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 8.h),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          DayHeaderCard(model: model),
          HeightSpace(16.h),
          PeriodSection(
            showDelete: showDelete,
            icon: AppAssets.sun,
            title: "in_morning".tr(),
            description: model.morning?.description ?? "",
            places: model.morning?.places ?? [],
            period: 'morning',
            dayIndex: dayIndex,
          ),
          HeightSpace(16.h),
          PeriodSection(
            showDelete: showDelete,
            icon: AppAssets.moonIcon,
            title: 'in_evening'.tr(),
            description: model.evening?.description ?? "",
            places: model.evening?.places ?? [],
            period: 'evening',
            dayIndex: dayIndex,
          ),
        ],
      ),
    );
  }
}
