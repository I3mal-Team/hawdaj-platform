import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/trip_model.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/start_trip_view_body.dart';

class FinishTrip extends StatelessWidget {
  const FinishTrip({super.key, this.tripModel, this.tripResponse});

  final TripModel? tripModel;
  final EnhancedTripResponse? tripResponse;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: StartTripViewBody(
        allowBack: true,
        title: "finish_trip_title".tr(),
        description: "finish_trip_description".tr(),
        onTap: () {
          print('tripResponse: ${tripResponse?.data?.token}');
          if (tripResponse != null) {
            push(
              RoutesKeys.kNewTripPlan,
              context,
              extra: tripResponse!.data?.token,
            );
          } else if (tripModel != null) {
            push(RoutesKeys.kTripPlan, context, extra: tripModel);
          } else {
            // Optional: Show error or fallback
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('لا توجد بيانات رحلة!')),
            );
          }
        },
        textbutton: 'finish_trip_button'.tr(),
        subtitle: 'finish_trip_subtitle'.tr(),
      ),
    );
  }
}
