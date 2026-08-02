import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/presentation/view/new_widgets/places_list.dart';

class PeriodSection extends StatelessWidget {
  const PeriodSection({
    super.key,
    required this.icon,
    required this.title,
    required this.description,
    required this.places,

    required this.period,
    required this.dayIndex,

    required this.showDelete,
  });

  final String icon;
  final String title;
  final String description;
  final List<Place> places;
  final int dayIndex;

  final String period;
  final bool showDelete;

  @override
  Widget build(BuildContext context) {
    if (places.isEmpty) {
      return const SizedBox.shrink();
    }
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(12),
      decoration: ShapeDecoration(
        shape: RoundedRectangleBorder(
          side: BorderSide(width: 1, color: const Color(0xFFE9E9EB)),
          borderRadius: BorderRadius.circular(12),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              SvgPicture.asset(icon),
              WidthSpace(8.w),
              Text(
                title,
                style: TextStyle(
                  color: const Color(0xFF262626),
                  fontSize: 16,
                  fontFamily: 'The Year of The Camel',
                  fontWeight: FontWeight.w800,
                  height: 1.50,
                ),
              ),
            ],
          ),
          HeightSpace(16.h),
          SizedBox(
            width: 337,
            child: Text(
              description,
              //textAlign: TextAlign.right,
              style: AppTextStyles.font14Medium.copyWith(
                color: const Color(0xFF717680),
                height: 1.43,
              ),
            ),
          ),
          HeightSpace(16.h),
          PlacesList(
            places: places,
            period: period,
            dayIndex: dayIndex,
            showDelete: showDelete,
          ),
        ],
      ),
    );
  }
}
