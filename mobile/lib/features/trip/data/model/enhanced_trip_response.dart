import 'package:json_annotation/json_annotation.dart';

part 'enhanced_trip_response.g.dart';

// ═══════════════════════════════════════════════════════════════
// Response الرئيسي
// ═══════════════════════════════════════════════════════════════

@JsonSerializable(
  explicitToJson: true,
  checked: false,
  fieldRename: FieldRename.snake,
)
class EnhancedTripResponse {
  @JsonKey(defaultValue: 200)
  final int code;

  @JsonKey(defaultValue: '')
  final String message;

  final EnhancedTripData? data;

  const EnhancedTripResponse({this.code = 200, this.message = '', this.data});

  factory EnhancedTripResponse.fromJson(Map<String, dynamic> json) =>
      _$EnhancedTripResponseFromJson(json);

  Map<String, dynamic> toJson() => _$EnhancedTripResponseToJson(this);

  @override
  String toString() {
    return 'EnhancedTripResponse{code: $code, message: $message, data: $data}';
  }
}

// ═══════════════════════════════════════════════════════════════
// بيانات الرحلة المحسّنة
// ═══════════════════════════════════════════════════════════════

@JsonSerializable(explicitToJson: true, fieldRename: FieldRename.snake)
class EnhancedTripData {
  @JsonKey(defaultValue: '')
  final String token;

  @JsonKey(defaultValue: '')
  final String startDate;

  @JsonKey(defaultValue: '')
  final String endDate;

  @JsonKey(fromJson: _toInt, defaultValue: 0)
  final int totalDays;

  @JsonKey(fromJson: _toString, defaultValue: '0')
  final String placesPerDay;

  @JsonKey(fromJson: _toInt, defaultValue: 0)
  final int placesPerPeriod;

  final Region? startRegion;
  final Region? endRegion;

  @JsonKey(defaultValue: [])
  final List<EnhancedDay> enhancedData;

  const EnhancedTripData({
    this.token = '',
    this.startDate = '',
    this.endDate = '',
    this.totalDays = 0,
    this.placesPerDay = '0',
    this.placesPerPeriod = 0,
    this.startRegion,
    this.endRegion,
    this.enhancedData = const [],
  });

  factory EnhancedTripData.fromJson(Map<String, dynamic> json) {
    final safeJson = Map<String, dynamic>.from(json);
    final days = safeJson['days'] ?? safeJson['enhanced_data'];

    return EnhancedTripData(
      token: safeJson['token'] ?? '',
      startDate: safeJson['start_date'] ?? '',
      endDate: safeJson['end_date'] ?? '',
      totalDays: _toInt(safeJson['total_days']),
      placesPerDay: _toString(safeJson['places_per_day']),
      placesPerPeriod: _toInt(safeJson['places_per_period']),
      startRegion: safeJson['start_region'] != null
          ? Region.fromJson(safeJson['start_region'])
          : null,
      endRegion: safeJson['end_region'] != null
          ? Region.fromJson(safeJson['end_region'])
          : null,
      enhancedData:
          (days as List?)?.map((e) => EnhancedDay.fromJson(e)).toList() ?? [],
    );
  }

  Map<String, dynamic> toJson() => _$EnhancedTripDataToJson(this);

  static int _toInt(dynamic value) {
    if (value is int) return value;
    if (value is String) return int.tryParse(value) ?? 0;
    return 0;
  }

  static String _toString(dynamic value) {
    if (value == null) return '0';
    return value.toString();
  }

  int get totalPlaces => enhancedData.fold(
    0,
    (sum, day) =>
        sum +
        (day.morning?.places.length ?? 0) +
        (day.evening?.places.length ?? 0),
  );

  Duration? get duration {
    try {
      return DateTime.parse(endDate).difference(DateTime.parse(startDate));
    } catch (_) {
      return null;
    }
  }
}

// ═══════════════════════════════════════════════════════════════
// يوم من الرحلة
// ═══════════════════════════════════════════════════════════════

@JsonSerializable(explicitToJson: true, fieldRename: FieldRename.snake)
class EnhancedDay {
  @JsonKey(defaultValue: 0)
  final int dayNumber;

  @JsonKey(defaultValue: '')
  final String date;

  final EnhancedPeriod? morning;
  final EnhancedPeriod? evening;
  final City? city;

  const EnhancedDay({
    this.dayNumber = 0,
    this.date = '',
    this.morning,
    this.evening,
    this.city,
  });

  factory EnhancedDay.fromJson(Map<String, dynamic> json) =>
      _$EnhancedDayFromJson(json);

  Map<String, dynamic> toJson() => _$EnhancedDayToJson(this);

  int get totalPlaces =>
      (morning?.places.length ?? 0) + (evening?.places.length ?? 0);

  List<Place> get allPlaces => [
    ...(morning?.places ?? []),
    ...(evening?.places ?? []),
  ];
}

// ═══════════════════════════════════════════════════════════════
// فترة من اليوم (صباح/مساء)
// ═══════════════════════════════════════════════════════════════

@JsonSerializable(explicitToJson: true, fieldRename: FieldRename.snake)
class EnhancedPeriod {
  @JsonKey(defaultValue: [])
  final List<Place> places;

  @JsonKey(defaultValue: '')
  final String description;

  const EnhancedPeriod({this.places = const [], this.description = ''});

  factory EnhancedPeriod.fromJson(Map<String, dynamic> json) =>
      _$EnhancedPeriodFromJson(json);

  Map<String, dynamic> toJson() => _$EnhancedPeriodToJson(this);
}

// ═══════════════════════════════════════════════════════════════
// المكان السياحي
// ═══════════════════════════════════════════════════════════════

@JsonSerializable(explicitToJson: true, fieldRename: FieldRename.snake)
class Place {
  final int? id;
  @JsonKey(defaultValue: '')
  final String slug;

  // ✅ FIX: Add custom converter for categories
  @JsonKey(fromJson: _categoriesToIntList, defaultValue: [])
  final List<int> categories;

  final String? address;
  final String? image;
  final String? title;
  final String? description;
  @JsonKey(fromJson: _toDoubleNullable, toJson: _fromDouble)
  final double? lat;
  @JsonKey(fromJson: _toDoubleNullable, toJson: _fromDouble)
  final double? long;
  final City? city;
  final Region? region;
  final String type;

  // ✅ FIX: Add custom converter for rate to handle String or num
  @JsonKey(fromJson: _toNum)
  final num? rate;

  // ✅ FIX: Add custom converter for distance to handle String or double (non-nullable)
  @JsonKey(fromJson: _toDoubleNonNull, defaultValue: 0.0)
  final double distance;

  @JsonKey(defaultValue: false)
  final bool visited;
  @JsonKey(defaultValue: false)
  final bool featured;
  final String? priceId;
  @JsonKey(defaultValue: [])
  final List<String> seasons;

  const Place({
    this.id,
    this.slug = '',
    this.categories = const [],
    this.address,
    this.image,
    this.title,
    this.description,
    this.lat,
    this.rate,
    this.long,
    this.city,
    this.type = '',
    this.region,
    this.distance = 0.0,
    this.visited = false,
    this.featured = false,
    this.priceId,
    this.seasons = const [],
  });

  // ✅ FIX: Custom converter to handle categories as objects or integers
  static List<int> _categoriesToIntList(dynamic value) {
    if (value == null) return [];
    if (value is! List) return [];

    return value
        .map((item) {
          if (item is int) return item;

          if (item is Map) {
            final id = item['id'];
            if (id is int) return id;
            if (id is String) return int.tryParse(id) ?? 0;
          }

          if (item is String) return int.tryParse(item) ?? 0;

          return 0;
        })
        .where((id) => id != 0)
        .toList();
  }

  // ✅ FIX: Custom converter to handle rate as String or num
  static num? _toNum(dynamic value) {
    if (value == null) return null;
    if (value is num) return value;
    if (value is String) {
      return num.tryParse(value);
    }
    return null;
  }

  // ✅ FIX: Converter for nullable double (for lat/long)
  static double? _toDoubleNullable(dynamic value) {
    if (value == null) return null;
    if (value is double) return value;
    if (value is int) return value.toDouble();
    if (value is String) return double.tryParse(value);
    return null;
  }

  // ✅ FIX: Converter for non-nullable double (for distance)
  static double _toDoubleNonNull(dynamic value) {
    if (value == null) return 0.0;
    if (value is double) return value;
    if (value is int) return value.toDouble();
    if (value is String) return double.tryParse(value) ?? 0.0;
    return 0.0;
  }

  factory Place.fromJson(Map<String, dynamic> json) {
    final safeJson = Map<String, dynamic>.from(json);

    if (safeJson['title'] == null && safeJson['name'] != null) {
      safeJson['title'] = safeJson['name'];
    }

    if (safeJson['city'] is String) {
      safeJson['city'] = {'name': safeJson['city']};
    }

    if (safeJson['region'] is String) {
      safeJson['region'] = {'name': safeJson['region']};
    }

    if (safeJson['id'] is String) {
      safeJson['id'] = int.tryParse(safeJson['id']);
    }

    return _$PlaceFromJson(safeJson);
  }

  Map<String, dynamic> toJson() => _$PlaceToJson(this);

  static dynamic _fromDouble(double? value) => value;

  bool get isFree => priceId == '1' || priceId == null;

  String get formattedDistance {
    if (distance < 1) {
      return '${(distance * 1000).toStringAsFixed(0)} م';
    }
    return '${distance.toStringAsFixed(1)} كم';
  }

  bool get hasCoordinates => lat != null && long != null;
}

// ═══════════════════════════════════════════════════════════════
// المنطقة
// ═══════════════════════════════════════════════════════════════

@JsonSerializable(fieldRename: FieldRename.snake)
class Region {
  final int? id;
  final String? name;

  const Region({this.id, this.name});

  factory Region.fromJson(Map<String, dynamic> json) => _$RegionFromJson(json);

  Map<String, dynamic> toJson() => _$RegionToJson(this);
}

// ═══════════════════════════════════════════════════════════════
// المدينة
// ═══════════════════════════════════════════════════════════════

@JsonSerializable(fieldRename: FieldRename.snake)
class City {
  final int? id;
  final String? name;
  final int? regionId;
  final String? description;

  const City({this.id, this.name, this.regionId, this.description});

  factory City.fromJson(Map<String, dynamic> json) => _$CityFromJson(json);

  Map<String, dynamic> toJson() => _$CityToJson(this);
}

// ═══════════════════════════════════════════════════════════════
// Copy Extensions
// ═══════════════════════════════════════════════════════════════

extension EnhancedTripResponseCopy on EnhancedTripResponse {
  EnhancedTripResponse copyWith({
    int? code,
    String? message,
    EnhancedTripData? data,
  }) {
    return EnhancedTripResponse(
      code: code ?? this.code,
      message: message ?? this.message,
      data: data ?? this.data,
    );
  }
}

extension EnhancedTripDataCopy on EnhancedTripData {
  EnhancedTripData copyWith({
    String? token,
    String? startDate,
    String? endDate,
    int? totalDays,
    String? placesPerDay,
    int? placesPerPeriod,
    Region? startRegion,
    Region? endRegion,
    List<EnhancedDay>? enhancedData,
  }) {
    return EnhancedTripData(
      token: token ?? this.token,
      startDate: startDate ?? this.startDate,
      endDate: endDate ?? this.endDate,
      totalDays: totalDays ?? this.totalDays,
      placesPerDay: placesPerDay ?? this.placesPerDay,
      placesPerPeriod: placesPerPeriod ?? this.placesPerPeriod,
      startRegion: startRegion ?? this.startRegion,
      endRegion: endRegion ?? this.endRegion,
      enhancedData: enhancedData ?? this.enhancedData,
    );
  }
}

extension EnhancedDayCopy on EnhancedDay {
  EnhancedDay copyWith({
    int? dayNumber,
    String? date,
    EnhancedPeriod? morning,
    EnhancedPeriod? evening,
    City? city,
  }) {
    return EnhancedDay(
      dayNumber: dayNumber ?? this.dayNumber,
      date: date ?? this.date,
      morning: morning ?? this.morning,
      evening: evening ?? this.evening,
      city: city ?? this.city,
    );
  }
}

extension EnhancedPeriodCopy on EnhancedPeriod {
  EnhancedPeriod copyWith({List<Place>? places, String? description}) {
    return EnhancedPeriod(
      places: places ?? this.places,
      description: description ?? this.description,
    );
  }
}
