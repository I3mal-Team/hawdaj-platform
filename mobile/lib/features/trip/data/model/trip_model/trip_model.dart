import 'dart:convert';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';

class TripModel extends Equatable {
  final String? selectedCategories;
  final String? season;
  final String? region1;
  final String? lat1;
  final String? long1;
  final String? lat2;
  final String? long2;
  final String? region2;

  final List<List<UnifiedPlaceModel>>? places;

  final int? days;
  final num? funnyPlacePerDay;
  final String? funny;

  final List<List<int>> items;

  final String? date;
  final String? type;
  final String? dateRange;
  final String? startDate;
  final String? endDate;
  final RegionObject? region1Object;
  final RegionObject? region2Object;
  final String? token;

  const TripModel({
    this.selectedCategories,
    this.season,
    this.region1,
    this.lat1,
    this.long1,
    this.lat2,
    this.long2,
    this.region2,
    this.places,
    this.days,
    this.funnyPlacePerDay,
    this.funny,
    this.items = const [],
    this.date,
    this.type,
    this.dateRange,
    this.startDate,
    this.endDate,
    this.region1Object,
    this.region2Object,
    this.token,
  });

  TripModel copyWith({
    String? selectedCategories,
    String? season,
    String? region1,
    String? lat1,
    String? long1,
    String? lat2,
    String? long2,
    String? region2,
    List<List<UnifiedPlaceModel>>? places,
    int? days,
    num? funnyPlacePerDay,
    String? funny,
    List<List<int>>? items,
    String? date,
    String? type,
    String? dateRange,
    String? startDate,
    String? endDate,
    RegionObject? region1Object,
    RegionObject? region2Object,
    String? token,
  }) {
    return TripModel(
      selectedCategories: selectedCategories ?? this.selectedCategories,
      season: season ?? this.season,
      region1: region1 ?? this.region1,
      lat1: lat1 ?? this.lat1,
      long1: long1 ?? this.long1,
      lat2: lat2 ?? this.lat2,
      long2: long2 ?? this.long2,
      region2: region2 ?? this.region2,
      places: places ?? this.places,
      days: days ?? this.days,
      funnyPlacePerDay: funnyPlacePerDay ?? this.funnyPlacePerDay,
      funny: funny ?? this.funny,
      items: items ?? this.items,
      date: date ?? this.date,
      type: type ?? this.type,
      dateRange: dateRange ?? this.dateRange,
      startDate: startDate ?? this.startDate,
      endDate: endDate ?? this.endDate,
      region1Object: region1Object ?? this.region1Object,
      region2Object: region2Object ?? this.region2Object,
      token: token ?? this.token,
    );
  }

  factory TripModel.fromJson(Map<String, dynamic> json) {
    final places = _parsePlaces(json['places']);
    final items = _parseItems(json['items']);

    return TripModel(
      selectedCategories: _asString(json['selected_categories']),
      season: _asString(json['season']),
      region1: _asString(json['region1']),
      lat1: _asString(json['lat1']),
      long1: _asString(json['long1']),
      lat2: _asString(json['lat2']),
      long2: _asString(json['long2']),
      region2: _asString(json['region2']),
      places: places,
      days: _asInt(json['days']),
      funnyPlacePerDay: _asNum(json['funny_place_per_day']),
      funny: _asString(json['funny']),
      items: items,
      date: _asString(json['date']),
      type: _asString(json['type']),
      dateRange: _asString(json['daterange']) ?? _asString(json['date_range']),
      startDate: _asString(json['start_date']),
      endDate: _asString(json['end_date']),
      region1Object: json['region1Object'] is Map<String, dynamic>
          ? RegionObject.fromJson(json['region1Object'])
          : null,
      region2Object: json['region2Object'] is Map<String, dynamic>
          ? RegionObject.fromJson(json['region2Object'])
          : null,
      token: _asString(json['token']),
    );
  }

  @override
  List<Object?> get props => [
    selectedCategories,
    season,
    region1,
    lat1,
    long1,
    lat2,
    long2,
    region2,
    places,
    days,
    funnyPlacePerDay,
    funny,
    items,
    date,
    type,
    dateRange,
    startDate,
    endDate,
    region1Object,
    region2Object,
    token,
  ];

  // ---------- Helpers ----------

  static String? _asString(dynamic v) => v == null ? null : v.toString();

  static int? _asInt(dynamic v) {
    if (v == null) return null;
    if (v is int) return v;
    if (v is num) return v.toInt();
    if (v is String) return int.tryParse(v);
    return null;
  }

  static num? _asNum(dynamic v) {
    if (v == null) return null;
    if (v is num) return v;
    if (v is String) return num.tryParse(v);
    return null;
  }

  /// يدعم:
  /// - List (متداخلة Maps)
  /// - null
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

  /// يدعم:
  /// - List<List<dynamic>> مثل [[1115],[162],...]
  /// - List<dynamic> مسطّح: [1115,162,...] (نحوّله [[..],[..]])
  /// - String JSON: "[[1115],[162],...]"
  /// - null → []
  static List<List<int>> _parseItems(dynamic raw) {
    if (raw == null) return const <List<int>>[];

    // String JSON
    if (raw is String) {
      final s = raw.trim();
      if (s.isEmpty) return const <List<int>>[];
      try {
        final decoded = jsonDecode(s);
        return _normalizeNestedIntList(decoded);
      } catch (_) {
        return const <List<int>>[];
      }
    }

    // List بأي شكل
    if (raw is List) {
      return _normalizeNestedIntList(raw);
    }

    return const <List<int>>[];
  }

  /// يحوّل أي raw إلى List<List<int>>:
  /// - لو inner عناصره ليست List → يجعل كل عنصر داخل List منفصلة.
  /// - يحاول تحويل أي عنصر إلى int.
  static List<List<int>> _normalizeNestedIntList(dynamic raw) {
    if (raw is! List) return const <List<int>>[];

    // مثال: [1115,162] → [[1115],[162]]
    if (raw.isNotEmpty && raw.first is! List) {
      return raw
          .map<List<int>>((e) => <int>[int.tryParse(e.toString()) ?? 0])
          .toList();
    }

    // مثال: [[1115],[162]] أو []
    return raw.map<List<int>>((inner) {
      if (inner is! List) return const <int>[];
      return inner.map<int>((e) => int.tryParse(e.toString()) ?? 0).toList();
    }).toList();
  }
}

// ================= Region models =================

class RegionObject extends Equatable {
  final int? id;
  final String? name;
  final String? createdAt;
  final String? updatedAt;
  final String? deletedAt;
  final List<RegionTranslation>? translations;

  const RegionObject({
    this.id,
    this.name,
    this.createdAt,
    this.updatedAt,
    this.deletedAt,
    this.translations,
  });

  RegionObject copyWith({
    int? id,
    String? name,
    String? createdAt,
    String? updatedAt,
    String? deletedAt,
    List<RegionTranslation>? translations,
  }) {
    return RegionObject(
      id: id ?? this.id,
      name: name ?? this.name,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
      deletedAt: deletedAt ?? this.deletedAt,
      translations: translations ?? this.translations,
    );
  }

  factory RegionObject.fromJson(Map<String, dynamic> json) => RegionObject(
    id: TripModel._asInt(json['id']),
    name: TripModel._asString(json['name']),
    createdAt: TripModel._asString(json['created_at']),
    updatedAt: TripModel._asString(json['updated_at']),
    deletedAt: TripModel._asString(json['deleted_at']),
    translations: (json['translations'] as List?)
        ?.map(
          (e) => RegionTranslation.fromJson(
            (e is Map<String, dynamic>) ? e : <String, dynamic>{},
          ),
        )
        .toList(),
  );

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'created_at': createdAt,
    'updated_at': updatedAt,
    'deleted_at': deletedAt,
    'translations': translations?.map((e) => e.toJson()).toList(),
  };

  String? nameForLocale(String locale) {
    final t = translations?.firstWhere(
      (e) => (e.locale ?? '').toLowerCase() == locale.toLowerCase(),
      orElse: () => const RegionTranslation(),
    );
    return t?.name ?? name;
  }

  @override
  List<Object?> get props => [
    id,
    name,
    createdAt,
    updatedAt,
    deletedAt,
    translations,
  ];
}

class RegionTranslation extends Equatable {
  final int? id;
  final int? regionId;
  final String? locale;
  final String? name;

  const RegionTranslation({this.id, this.regionId, this.locale, this.name});

  RegionTranslation copyWith({
    int? id,
    int? regionId,
    String? locale,
    String? name,
  }) {
    return RegionTranslation(
      id: id ?? this.id,
      regionId: regionId ?? this.regionId,
      locale: locale ?? this.locale,
      name: name ?? this.name,
    );
  }

  factory RegionTranslation.fromJson(Map<String, dynamic> json) =>
      RegionTranslation(
        id: TripModel._asInt(json['id']),
        regionId: TripModel._asInt(json['region_id']),
        locale: TripModel._asString(json['locale']),
        name: TripModel._asString(json['name']),
      );

  Map<String, dynamic> toJson() => {
    'id': id,
    'region_id': regionId,
    'locale': locale,
    'name': name,
  };

  @override
  List<Object?> get props => [id, regionId, locale, name];
}
