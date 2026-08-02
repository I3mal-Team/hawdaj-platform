class UpdateGuidePhotoModel {
  final int? id;
  final String? firstName;
  final String? lastName;
  final String? email;
  final String? phone;
  final String? photo;
  final String? gender;
  final String? fullName;
  final int? totalPoints;

  UpdateGuidePhotoModel({
    this.id,
    this.firstName,
    this.lastName,
    this.email,
    this.phone,
    this.photo,
    this.gender,
    this.fullName,
    this.totalPoints,
  });

  factory UpdateGuidePhotoModel.fromJson(Map<String, dynamic> json) {
    return UpdateGuidePhotoModel(
      id: json['id'] as int?,
      firstName: json['first_name'] as String?,
      lastName: json['last_name'] as String?,
      email: json['email'] as String?,
      phone: json['phone'] as String?,
      photo: json['photo'] as String?,
      gender: json['gender'] as String?,
      fullName: json['full_name'] as String?,
      totalPoints: json['total_points'] as int?,
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
      'total_points': totalPoints,
    };
  }
}
