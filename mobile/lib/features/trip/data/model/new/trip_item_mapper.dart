import 'package:hawdaj/features/trip/data/model/my_trip_model.dart' as my_model;
import 'package:hawdaj/features/trip/data/model/new/new_my_trips_response.dart';

extension TripItemMapper on TripItem {
  my_model.MyTripModel toMyTripModel() {
    return my_model.MyTripModel(
      name: name,
      itemPerDay: placesPerDay,
      days: totalDays,
      items: '', // هذا غير متوفر في TripItem
      date: createdAt,
      userId: 0,
      createdAt: createdAt,
      email: null,
      token: token,
      startDate: startDate,
      endDate: endDate,
      region1Object: my_model.Region(
        id: startRegion.id,
        name: startRegion.name,
      ),
      region2Object: my_model.Region(id: endRegion.id, name: endRegion.name),
    );
  }
}
