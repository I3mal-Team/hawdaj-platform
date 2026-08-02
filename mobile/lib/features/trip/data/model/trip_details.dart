import 'dart:convert';

import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/trip/data/model/my_trip_model.dart';

class TripDetailsModel {
  final String funny;
  final String? type;
  final Trip trip;
  final String date;
  final String days;
  final String daterange;

  final List<List<UnifiedPlaceModel>>? places;
  final String funnyPlacePerDay;
  final String? startDate;
  final String? endDate;
  final num? region1;
  final num? region2;
  final Region region1Object;
  final Region region2Object;

  TripDetailsModel({
    required this.funny,
    required this.type,
    required this.trip,
    required this.date,
    required this.days,
    required this.daterange,
    required this.funnyPlacePerDay,
    this.startDate,
    this.endDate,
    this.region1,
    this.region2,
    required this.region1Object,
    this.places,

    required this.region2Object,
  });
  static List<List<UnifiedPlaceModel>>? _parsePlaces(dynamic raw) {
    if (raw == null) return null;
    if (raw is! List) return null;

    return raw.map<List<UnifiedPlaceModel>>((inner) {
      if (inner is! List) return <UnifiedPlaceModel>[];
      return inner.map<UnifiedPlaceModel>((e) {
        // بعض الـ APIs ممكن ترجع object جاهز، أو Map
        if (e is UnifiedPlaceModel) return e;
        if (e is Map<String, dynamic>) {
          return UnifiedPlaceModel.fromJson(e);
        }
        // أي نوع تاني نتجاهله
        return UnifiedPlaceModel.fromJson(const {});
      }).toList();
    }).toList();
  }

  factory TripDetailsModel.fromJson(Map<String, dynamic> json) {
    final places = _parsePlaces(json['places']);

    return TripDetailsModel(
      funny: json['funny'],
      type: json['type'],
      trip: Trip.fromJson(json['trip']),
      date: json['date'],
      days: json['days'],
      places: places,
      daterange: json['daterange'],
      funnyPlacePerDay: json['funny_place_per_day'],
      startDate: json['start_date'],
      endDate: json['end_date'],
      region1: json['region1'],
      region2: json['region2'],
      region1Object: Region.fromJson(json['region1Object'] ?? {}),
      region2Object: Region.fromJson(json['region2Object'] ?? {}),
    );
  }
}

class Trip {
  final int id;
  final String name;
  final String itemPerDay;
  final String days;
  final List<List<int>> items;
  final String date;
  final int userId;
  final DateTime createdAt;
  final DateTime updatedAt;
  final String? email;
  final String token;
  final String? startDate;
  final String? endDate;
  final num? region1;
  final num? region2;

  Trip({
    required this.id,
    required this.name,
    required this.itemPerDay,
    required this.days,
    required this.items,
    required this.date,
    required this.userId,
    required this.createdAt,
    required this.updatedAt,
    this.email,
    required this.token,
    this.startDate,
    this.endDate,
    this.region1,
    this.region2,
  });

  factory Trip.fromJson(Map<String, dynamic> json) {
    final rawItems = json['items'];
    final parsedItems = rawItems is String
        ? (jsonDecode(rawItems) as List)
              .map<List<int>>((e) => List<int>.from(e))
              .toList()
        : <List<int>>[];

    return Trip(
      id: json['id'],
      name: json['name'],
      itemPerDay: json['item_per_day'],
      days: json['days'],
      items: parsedItems,
      date: json['date'],
      userId: json['user_id'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
      email: json['email'],
      token: json['token'],
      startDate: json['start_date'],
      endDate: json['end_date'],
      region1: json['region1'],
      region2: json['region2'],
    );
  }
}
