// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'new_my_trips_response.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

NewMyTripsResponse _$NewMyTripsResponseFromJson(Map<String, dynamic> json) =>
    NewMyTripsResponse(
      code: (json['code'] as num).toInt(),
      message: json['message'] as String,
      data: MyTripsData.fromJson(json['data'] as Map<String, dynamic>),
    );

Map<String, dynamic> _$NewMyTripsResponseToJson(NewMyTripsResponse instance) =>
    <String, dynamic>{
      'code': instance.code,
      'message': instance.message,
      'data': instance.data.toJson(),
    };

MyTripsData _$MyTripsDataFromJson(Map<String, dynamic> json) => MyTripsData(
  trips: (json['trips'] as List<dynamic>)
      .map((e) => TripItem.fromJson(e as Map<String, dynamic>))
      .toList(),
  pagination: Pagination.fromJson(json['pagination'] as Map<String, dynamic>),
);

Map<String, dynamic> _$MyTripsDataToJson(MyTripsData instance) =>
    <String, dynamic>{
      'trips': instance.trips.map((e) => e.toJson()).toList(),
      'pagination': instance.pagination.toJson(),
    };

TripItem _$TripItemFromJson(Map<String, dynamic> json) => TripItem(
  id: (json['id'] as num).toInt(),
  name: json['name'] as String,
  token: json['token'] as String,
  startDate: json['start_date'] as String,
  endDate: json['end_date'] as String,
  totalDays: json['total_days'] as String,
  placesPerDay: json['places_per_day'] as String,
  placesPerPeriod: (json['places_per_period'] as num).toInt(),
  totalPlaces: (json['total_places'] as num).toInt(),
  startRegion: Region.fromJson(json['start_region'] as Map<String, dynamic>),
  endRegion: Region.fromJson(json['end_region'] as Map<String, dynamic>),
  createdAt: json['created_at'] as String,
);

Map<String, dynamic> _$TripItemToJson(TripItem instance) => <String, dynamic>{
  'id': instance.id,
  'name': instance.name,
  'token': instance.token,
  'start_date': instance.startDate,
  'end_date': instance.endDate,
  'total_days': instance.totalDays,
  'places_per_day': instance.placesPerDay,
  'places_per_period': instance.placesPerPeriod,
  'total_places': instance.totalPlaces,
  'start_region': instance.startRegion,
  'end_region': instance.endRegion,
  'created_at': instance.createdAt,
};

Region _$RegionFromJson(Map<String, dynamic> json) =>
    Region(id: (json['id'] as num).toInt(), name: json['name'] as String);

Map<String, dynamic> _$RegionToJson(Region instance) => <String, dynamic>{
  'id': instance.id,
  'name': instance.name,
};

Pagination _$PaginationFromJson(Map<String, dynamic> json) => Pagination(
  currentPage: (json['current_page'] as num).toInt(),
  lastPage: (json['last_page'] as num).toInt(),
  perPage: (json['per_page'] as num).toInt(),
  total: (json['total'] as num).toInt(),
);

Map<String, dynamic> _$PaginationToJson(Pagination instance) =>
    <String, dynamic>{
      'current_page': instance.currentPage,
      'last_page': instance.lastPage,
      'per_page': instance.perPage,
      'total': instance.total,
    };
