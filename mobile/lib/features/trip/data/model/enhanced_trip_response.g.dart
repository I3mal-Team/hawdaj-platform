// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'enhanced_trip_response.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

EnhancedTripResponse _$EnhancedTripResponseFromJson(
  Map<String, dynamic> json,
) => EnhancedTripResponse(
  code: (json['code'] as num?)?.toInt() ?? 200,
  message: json['message'] as String? ?? '',
  data: json['data'] == null
      ? null
      : EnhancedTripData.fromJson(json['data'] as Map<String, dynamic>),
);

Map<String, dynamic> _$EnhancedTripResponseToJson(
  EnhancedTripResponse instance,
) => <String, dynamic>{
  'code': instance.code,
  'message': instance.message,
  'data': instance.data?.toJson(),
};

EnhancedTripData _$EnhancedTripDataFromJson(Map<String, dynamic> json) =>
    EnhancedTripData(
      token: json['token'] as String? ?? '',
      startDate: json['start_date'] as String? ?? '',
      endDate: json['end_date'] as String? ?? '',
      totalDays: json['total_days'] == null
          ? 0
          : EnhancedTripData._toInt(json['total_days']),
      placesPerDay: json['places_per_day'] == null
          ? '0'
          : EnhancedTripData._toString(json['places_per_day']),
      placesPerPeriod: json['places_per_period'] == null
          ? 0
          : EnhancedTripData._toInt(json['places_per_period']),
      startRegion: json['start_region'] == null
          ? null
          : Region.fromJson(json['start_region'] as Map<String, dynamic>),
      endRegion: json['end_region'] == null
          ? null
          : Region.fromJson(json['end_region'] as Map<String, dynamic>),
      enhancedData:
          (json['enhanced_data'] as List<dynamic>?)
              ?.map((e) => EnhancedDay.fromJson(e as Map<String, dynamic>))
              .toList() ??
          [],
    );

Map<String, dynamic> _$EnhancedTripDataToJson(EnhancedTripData instance) =>
    <String, dynamic>{
      'token': instance.token,
      'start_date': instance.startDate,
      'end_date': instance.endDate,
      'total_days': instance.totalDays,
      'places_per_day': instance.placesPerDay,
      'places_per_period': instance.placesPerPeriod,
      'start_region': instance.startRegion?.toJson(),
      'end_region': instance.endRegion?.toJson(),
      'enhanced_data': instance.enhancedData.map((e) => e.toJson()).toList(),
    };

EnhancedDay _$EnhancedDayFromJson(Map<String, dynamic> json) => EnhancedDay(
  dayNumber: (json['day_number'] as num?)?.toInt() ?? 0,
  date: json['date'] as String? ?? '',
  morning: json['morning'] == null
      ? null
      : EnhancedPeriod.fromJson(json['morning'] as Map<String, dynamic>),
  evening: json['evening'] == null
      ? null
      : EnhancedPeriod.fromJson(json['evening'] as Map<String, dynamic>),
  city: json['city'] == null
      ? null
      : City.fromJson(json['city'] as Map<String, dynamic>),
);

Map<String, dynamic> _$EnhancedDayToJson(EnhancedDay instance) =>
    <String, dynamic>{
      'day_number': instance.dayNumber,
      'date': instance.date,
      'morning': instance.morning?.toJson(),
      'evening': instance.evening?.toJson(),
      'city': instance.city?.toJson(),
    };

EnhancedPeriod _$EnhancedPeriodFromJson(Map<String, dynamic> json) =>
    EnhancedPeriod(
      places:
          (json['places'] as List<dynamic>?)
              ?.map((e) => Place.fromJson(e as Map<String, dynamic>))
              .toList() ??
          [],
      description: json['description'] as String? ?? '',
    );

Map<String, dynamic> _$EnhancedPeriodToJson(EnhancedPeriod instance) =>
    <String, dynamic>{
      'places': instance.places.map((e) => e.toJson()).toList(),
      'description': instance.description,
    };

Place _$PlaceFromJson(Map<String, dynamic> json) => Place(
  id: (json['id'] as num?)?.toInt(),
  slug: json['slug'] as String? ?? '',
  categories: json['categories'] == null
      ? []
      : Place._categoriesToIntList(json['categories']),
  address: json['address'] as String?,
  image: json['image'] as String?,
  title: json['title'] as String?,
  description: json['description'] as String?,
  lat: Place._toDoubleNullable(json['lat']),
  rate: Place._toNum(json['rate']),
  long: Place._toDoubleNullable(json['long']),
  city: json['city'] == null
      ? null
      : City.fromJson(json['city'] as Map<String, dynamic>),
  type: json['type'] as String? ?? '',
  region: json['region'] == null
      ? null
      : Region.fromJson(json['region'] as Map<String, dynamic>),
  distance: json['distance'] == null
      ? 0.0
      : Place._toDoubleNonNull(json['distance']),
  visited: json['visited'] as bool? ?? false,
  featured: json['featured'] as bool? ?? false,
  priceId: json['price_id'] as String?,
  seasons:
      (json['seasons'] as List<dynamic>?)?.map((e) => e as String).toList() ??
      [],
);

Map<String, dynamic> _$PlaceToJson(Place instance) => <String, dynamic>{
  'id': instance.id,
  'slug': instance.slug,
  'categories': instance.categories,
  'address': instance.address,
  'image': instance.image,
  'title': instance.title,
  'description': instance.description,
  'lat': Place._fromDouble(instance.lat),
  'long': Place._fromDouble(instance.long),
  'city': instance.city?.toJson(),
  'region': instance.region?.toJson(),
  'type': instance.type,
  'rate': instance.rate,
  'distance': instance.distance,
  'visited': instance.visited,
  'featured': instance.featured,
  'price_id': instance.priceId,
  'seasons': instance.seasons,
};

Region _$RegionFromJson(Map<String, dynamic> json) =>
    Region(id: (json['id'] as num?)?.toInt(), name: json['name'] as String?);

Map<String, dynamic> _$RegionToJson(Region instance) => <String, dynamic>{
  'id': instance.id,
  'name': instance.name,
};

City _$CityFromJson(Map<String, dynamic> json) => City(
  id: (json['id'] as num?)?.toInt(),
  name: json['name'] as String?,
  regionId: (json['region_id'] as num?)?.toInt(),
  description: json['description'] as String?,
);

Map<String, dynamic> _$CityToJson(City instance) => <String, dynamic>{
  'id': instance.id,
  'name': instance.name,
  'region_id': instance.regionId,
  'description': instance.description,
};
