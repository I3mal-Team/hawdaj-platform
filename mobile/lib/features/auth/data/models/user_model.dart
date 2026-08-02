class UserModel {
  final int? id;
  final String? firstName;
  final String? lastName;
  final String? email;
  final String? phone;
  final String? photo;
  final String? gender;
  final String? fullName;
  final String? createdAt;
  final String? updatedAt;
  final String? deletedAt;
  final String? providerId;
  final String? providerType;
  final String? apiToken;
  final String? forgetPasswordCode;
  final String? totalPoints;

  UserModel({
    this.id,
    this.firstName,
    this.lastName,
    this.email,
    this.phone,
    this.photo,
    this.gender,
    this.fullName,
    this.createdAt,
    this.updatedAt,
    this.deletedAt,
    this.providerId,
    this.providerType,
    this.apiToken,
    this.forgetPasswordCode,
    this.totalPoints,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'],
      firstName: json['first_name']?.toString(),
      lastName: json['last_name']?.toString(),
      email: json['email']?.toString(),
      phone: json['phone']?.toString(),
      photo: json['photo']?.toString(),
      gender: json['gender']?.toString(),
      fullName: json['full_name']?.toString(),
      createdAt: json['created_at']?.toString(),
      updatedAt: json['updated_at']?.toString(),
      deletedAt: json['deleted_at']?.toString(),
      providerId: json['provider_id']?.toString(),
      providerType: json['provider_type']?.toString(),
      apiToken: json['api_token']?.toString(),
      forgetPasswordCode: json['forget_password_code']?.toString(),
      totalPoints: json['total_points']?.toString(),
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
      'full_name': fullName,
      'created_at': createdAt,
      'updated_at': updatedAt,
      'deleted_at': deletedAt,
      'provider_id': providerId,
      'provider_type': providerType,
      'api_token': apiToken,
      'forget_password_code': forgetPasswordCode,
      'total_points': totalPoints,
    };
  }
}
