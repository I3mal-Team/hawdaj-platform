import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/trip/presentation/manager/map_trip_cubit/map_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/map_trip_view_body.dart';

class MapTripView extends StatelessWidget {
  const MapTripView({super.key, this.dailyGroups});
  final List<List<UnifiedPlaceModel>>? dailyGroups;

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => MapTripCubit()
        ..setDayGroups(dailyGroups ?? const []), // يحقن الجروبات ويعرض الماركرز
      child: const MapTripViewBody(),
    );
  }
}
