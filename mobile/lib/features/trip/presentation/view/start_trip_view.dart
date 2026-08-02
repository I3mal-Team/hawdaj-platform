import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/start_trip_view_body.dart';

class StartTripView extends StatelessWidget {
  const StartTripView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: StartTripViewBody(
        title: 'start_trip_title'.tr(context: context),
        description: 'start_trip_description'.tr(context: context),
        onTap: () {
          push(RoutesKeys.kAddTripView, context);
        },
        textbutton: 'start_trip_button'.tr(context: context),
        subtitle: 'start_trip_subtitle'.tr(context: context),
      ),
    );
  }
}
