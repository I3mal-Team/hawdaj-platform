import 'package:json_annotation/json_annotation.dart';

part 'new_my_trips_response.g.dart';

@JsonSerializable(explicitToJson: true, fieldRename: FieldRename.snake)
class NewMyTripsResponse {
  final int code;
  final String message;
  final MyTripsData data;

  const NewMyTripsResponse({
    required this.code,
    required this.message,
    required this.data,
  });

  factory NewMyTripsResponse.fromJson(Map<String, dynamic> json) =>
      _$NewMyTripsResponseFromJson(json);

  Map<String, dynamic> toJson() => _$NewMyTripsResponseToJson(this);
}

@JsonSerializable(explicitToJson: true, fieldRename: FieldRename.snake)
class MyTripsData {
  final List<TripItem> trips;
  final Pagination pagination;

  const MyTripsData({required this.trips, required this.pagination});

  factory MyTripsData.fromJson(Map<String, dynamic> json) =>
      _$MyTripsDataFromJson(json);

  Map<String, dynamic> toJson() => _$MyTripsDataToJson(this);
}

@JsonSerializable(fieldRename: FieldRename.snake)
class TripItem {
  final int id;
  final String name;
  final String token;
  final String startDate;
  final String endDate;
  final String totalDays;
  final String placesPerDay;
  final int placesPerPeriod;
  final int totalPlaces;
  final Region startRegion;
  final Region endRegion;
  final String createdAt;

  const TripItem({
    required this.id,
    required this.name,
    required this.token,
    required this.startDate,
    required this.endDate,
    required this.totalDays,
    required this.placesPerDay,
    required this.placesPerPeriod,
    required this.totalPlaces,
    required this.startRegion,
    required this.endRegion,
    required this.createdAt,
  });

  factory TripItem.fromJson(Map<String, dynamic> json) =>
      _$TripItemFromJson(json);

  Map<String, dynamic> toJson() => _$TripItemToJson(this);
}

@JsonSerializable(fieldRename: FieldRename.snake)
class Region {
  final int id;
  final String name;

  const Region({required this.id, required this.name});

  factory Region.fromJson(Map<String, dynamic> json) => _$RegionFromJson(json);

  Map<String, dynamic> toJson() => _$RegionToJson(this);
}

@JsonSerializable(fieldRename: FieldRename.snake)
class Pagination {
  final int currentPage;
  final int lastPage;
  final int perPage;
  final int total;

  const Pagination({
    required this.currentPage,
    required this.lastPage,
    required this.perPage,
    required this.total,
  });

  factory Pagination.fromJson(Map<String, dynamic> json) =>
      _$PaginationFromJson(json);

  Map<String, dynamic> toJson() => _$PaginationToJson(this);
}
