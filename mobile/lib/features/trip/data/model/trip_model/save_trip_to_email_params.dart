import 'dart:convert';

class SaveTripToEmailParams {
  final String date;
  final String days;
  final String? email;
  final String endDate;
  final String itemPerDay;
  final List<List<int>> items;
  final String name;
  final String region1;
  final String region2;
  final String startDate;
  final String? userName;
  final String? prepareToken;
  final int? userId;

  const SaveTripToEmailParams({
    required this.date,
    required this.days,
    required this.email,
    required this.endDate,
    required this.itemPerDay,
    required this.items,
    required this.name,
    required this.region1,
    required this.region2,
    required this.startDate,
    required this.userName,
    this.prepareToken,
    this.userId,
  });

  factory SaveTripToEmailParams.fromJson(Map<String, dynamic> json) {
    return SaveTripToEmailParams(
      date: (json['date'] ?? '').toString(),
      days: (json['days'] ?? '').toString(),
      email: json['email']?.toString(),
      endDate: (json['end_date'] ?? '').toString(),
      itemPerDay: (json['item_per_day'] ?? '').toString(),
      items: _parseItems(json['items']),
      name: (json['name'] ?? '').toString(),
      region1: (json['region1'] ?? '').toString(),
      region2: (json['region2'] ?? '').toString(),
      startDate: (json['start_date'] ?? '').toString(),
      userName: json['user_name']?.toString(),
      prepareToken: json['prepare_token']?.toString(),
      userId: _parseUserId(json['user_id']),
    );
  }

  Map<String, dynamic> toJson() {
    final map = {
      'date': date,
      'days': days,
      'end_date': endDate,
      'item_per_day': itemPerDay,
      'items': jsonEncode(items),
      'name': name,
      'region1': region1,
      'region2': region2,
      'start_date': startDate,
    };
    if (email != null) map['email'] = email.toString();
    if (userName != null) map['user_name'] = userName.toString();
    if (prepareToken != null) map['prepare_token'] = prepareToken.toString();

    if (userId != null) map['user_id'] = userId.toString();

    return map;
  }

  Map<String, dynamic> toPureJsonBody() {
    final map = {
      'date': date,
      'days': days,
      'end_date': endDate,
      'item_per_day': itemPerDay,
      'items': items,
      'name': name,
      'region1': region1,
      'region2': region2,
      'start_date': startDate,
    };

    if (email != null) map['email'] = email.toString();
    if (userName != null) map['user_name'] = userName.toString();
    if (prepareToken != null) map['prepare_token'] = prepareToken.toString();

    if (userId != null) map['user_id'] = userId.toString();

    return map;
  }

  SaveTripToEmailParams copyWith({
    String? date,
    String? days,
    String? email,
    String? endDate,
    String? itemPerDay,
    List<List<int>>? items,
    String? name,
    String? region1,
    String? region2,
    String? startDate,
    String? userName,
    String? prepareToken,
    int? userId,
  }) {
    return SaveTripToEmailParams(
      date: date ?? this.date,
      days: days ?? this.days,
      email: email ?? this.email,
      endDate: endDate ?? this.endDate,
      itemPerDay: itemPerDay ?? this.itemPerDay,
      items: items ?? this.items,
      name: name ?? this.name,
      region1: region1 ?? this.region1,
      region2: region2 ?? this.region2,
      startDate: startDate ?? this.startDate,
      userName: userName ?? this.userName,
      prepareToken: prepareToken ?? this.prepareToken,
      userId: userId ?? this.userId,
    );
  }

  static int? _parseUserId(dynamic raw) {
    if (raw == null) return null;
    if (raw is int) return raw;
    if (raw is String) return int.tryParse(raw);
    return null;
  }

  static List<List<int>> _parseItems(dynamic raw) {
    if (raw == null) return const <List<int>>[];

    try {
      if (raw is String) {
        final s = raw.trim();
        if (s.isEmpty) return const <List<int>>[];
        final decoded = jsonDecode(s);
        return _normalizeNestedIntList(decoded);
      }

      if (raw is List) {
        return _normalizeNestedIntList(raw);
      }
    } catch (_) {}

    return const <List<int>>[];
  }

  static List<List<int>> _normalizeNestedIntList(dynamic raw) {
    if (raw is! List) return const <List<int>>[];

    return raw.map<List<int>>((inner) {
      if (inner is! List) return const <int>[];
      return inner.map<int>((e) => int.tryParse(e.toString()) ?? 0).toList();
    }).toList();
  }
}
