class MyTripModel {
  final String name;
  final String itemPerDay;
  final String days;
  final String items; // جاي كنص JSON
  final String date;
  final int userId;
  final String createdAt;
  final String? email;
  final String token;
  final String startDate;
  final String endDate;
  final Region region1Object;
  final Region region2Object;

  const MyTripModel({
    required this.name,
    required this.itemPerDay,
    required this.days,
    required this.items,
    required this.date,
    required this.userId,
    required this.createdAt,
    this.email,
    required this.token,
    required this.startDate,
    required this.endDate,
    required this.region1Object,
    required this.region2Object,
  });

  factory MyTripModel.fromJson(Map<String, dynamic> json) {
    return MyTripModel(
      name: json['name'] ?? '',
      itemPerDay: json['item_per_day']?.toString() ?? '',
      days: json['days']?.toString() ?? '',
      items: json['items'] ?? '',
      date: json['date'] ?? '',
      userId: json['user_id'] is int
          ? json['user_id']
          : int.tryParse(json['user_id'].toString()) ?? 0,
      createdAt: json['created_at'] ?? '',
      email: json['email'],
      token: json['token'] ?? '',
      startDate: json['start_date'] ?? '',
      endDate: json['end_date'] ?? '',
      region1Object: Region.fromJson(json['region1Object'] ?? {}),
      region2Object: Region.fromJson(json['region2Object'] ?? {}),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'item_per_day': itemPerDay,
      'days': days,
      'items': items,
      'date': date,
      'user_id': userId,
      'created_at': createdAt,
      'email': email,
      'token': token,
      'start_date': startDate,
      'end_date': endDate,
      'region1Object': region1Object.toJson(),
      'region2Object': region2Object.toJson(),
    };
  }
}

class Region {
  final int id;
  final String name;

  const Region({required this.id, required this.name});

  factory Region.fromJson(Map<String, dynamic> json) {
    return Region(
      id: json['id'] is int
          ? json['id']
          : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {'id': id, 'name': name};
  }
}
