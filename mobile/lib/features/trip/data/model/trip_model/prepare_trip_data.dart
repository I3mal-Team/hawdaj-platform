class TripData {
  final String? name;
  final List<List<int>> items;
  final dynamic places;
  final String selectedCategories;
  final String startDate;
  final String endDate;
  final String funny;
  final String funnyPlacePerDay;
  final String days;
  final int region1;
  final Region region1Object;
  final int region2;
  final Region region2Object;
  final String lat1;
  final String long1;
  final String lat2;
  final String long2;
  final int? userId;
  final User user;
  final String token;

  TripData({
    this.name,
    required this.items,
    this.places,
    required this.selectedCategories,
    required this.startDate,
    required this.endDate,
    required this.funny,
    required this.funnyPlacePerDay,
    required this.days,
    required this.region1,
    required this.region1Object,
    required this.region2,
    required this.region2Object,
    required this.lat1,
    required this.long1,
    required this.lat2,
    required this.long2,
    required this.userId,
    required this.user,
    required this.token,
  });

  factory TripData.fromJson(Map<String, dynamic> json) {
    return TripData(
      name: json['name'],
      items: (json['items'] as List)
          .map((e) => List<int>.from(e.map((x) => x as int)))
          .toList(),
      places: json['places'],
      selectedCategories: json['selected_categories'] ?? '',
      startDate: json['start_date'],
      endDate: json['end_date'],
      funny: json['funny'],
      funnyPlacePerDay: json['funny_place_per_day'],
      days: json['days'],
      region1: json['region1'],
      region1Object: Region.fromJson(json['region1Object']),
      region2: json['region2'],
      region2Object: Region.fromJson(json['region2Object']),
      lat1: json['lat1'],
      long1: json['long1'],
      lat2: json['lat2'],
      long2: json['long2'],
      userId: json['user_id'],
      user: User.fromJson(json['user'] ?? {}),
      token: json['token'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'items': items,
      'places': places,
      'selected_categories': selectedCategories,
      'start_date': startDate,
      'end_date': endDate,
      'funny': funny,
      'funny_place_per_day': funnyPlacePerDay,
      'days': days,
      'region1': region1,
      'region1Object': region1Object.toJson(),
      'region2': region2,
      'region2Object': region2Object.toJson(),
      'lat1': lat1,
      'long1': long1,
      'lat2': lat2,
      'long2': long2,
      'user_id': userId,
      'user': user.toJson(),
      'token': token,
    };
  }
}

class Region {
  final int id;
  final String name;

  Region({required this.id, required this.name});

  factory Region.fromJson(Map<String, dynamic> json) {
    return Region(id: json['id'], name: json['name']);
  }

  Map<String, dynamic> toJson() {
    return {'id': id, 'name': name};
  }
}

class User {
  final int? id;
  final String? firstName;
  final String? lastName;
  final String? email;
  final String? phone;
  final String? photo;
  final String? gender;
  final String? createdAt;
  final String? updatedAt;
  final String? deletedAt;
  final String? providerId;
  final String? providerType;
  final String? apiToken;
  final String? forgetPasswordCode;
  final dynamic totalPoints;
  final String? fullName;

  const User({
    this.id,
    this.firstName,
    this.lastName,
    this.email,
    this.phone,
    this.photo,
    this.gender,
    this.createdAt,
    this.updatedAt,
    this.deletedAt,
    this.providerId,
    this.providerType,
    this.apiToken,
    this.forgetPasswordCode,
    this.totalPoints,
    this.fullName,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] is int
          ? json['id']
          : int.tryParse(json['id']?.toString() ?? ''),
      firstName: json['first_name']?.toString(),
      lastName: json['last_name']?.toString(),
      email: json['email']?.toString(),
      phone: json['phone']?.toString(),
      photo: json['photo']?.toString(),
      gender: json['gender']?.toString(),
      createdAt: json['created_at']?.toString(),
      updatedAt: json['updated_at']?.toString(),
      deletedAt: json['deleted_at']?.toString(),
      providerId: json['provider_id']?.toString(),
      providerType: json['provider_type']?.toString(),
      apiToken: json['api_token']?.toString(),
      forgetPasswordCode: json['forget_password_code']?.toString(),
      totalPoints: json['total_points'],
      fullName: json['full_name']?.toString(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'first_name': firstName,
      'last_name': lastName,
      'email': email,
      'phone': phone,
      'photo': photo,
      'gender': gender,
      'created_at': createdAt,
      'updated_at': updatedAt,
      'deleted_at': deletedAt,
      'provider_id': providerId,
      'provider_type': providerType,
      'api_token': apiToken,
      'forget_password_code': forgetPasswordCode,
      'total_points': totalPoints,
      'full_name': fullName,
    };
  }

  User copyWith({
    int? id,
    String? firstName,
    String? lastName,
    String? email,
    String? phone,
    String? photo,
    String? gender,
    String? createdAt,
    String? updatedAt,
    String? deletedAt,
    String? providerId,
    String? providerType,
    String? apiToken,
    String? forgetPasswordCode,
    dynamic totalPoints,
    String? fullName,
  }) {
    return User(
      id: id ?? this.id,
      firstName: firstName ?? this.firstName,
      lastName: lastName ?? this.lastName,
      email: email ?? this.email,
      phone: phone ?? this.phone,
      photo: photo ?? this.photo,
      gender: gender ?? this.gender,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
      deletedAt: deletedAt ?? this.deletedAt,
      providerId: providerId ?? this.providerId,
      providerType: providerType ?? this.providerType,
      apiToken: apiToken ?? this.apiToken,
      forgetPasswordCode: forgetPasswordCode ?? this.forgetPasswordCode,
      totalPoints: totalPoints ?? this.totalPoints,
      fullName: fullName ?? this.fullName,
    );
  }
}
