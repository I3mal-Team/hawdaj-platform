import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/custom_trip_details_prices.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/places_count_container.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/transportation_options_widget.dart';

class CustomWidgetsHeadTripDetails extends StatelessWidget {
  const CustomWidgetsHeadTripDetails({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.start,
        children: [
          const CustomTripDetailsPrices(),
          HeightSpace(12.h),
          const PlacesCountContainer(),
          HeightSpace(12.h),
          const TransportationOptionsWidget(),
        ],
      ),
    );
  }
}
