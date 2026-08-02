import 'user_model.dart';

class UpdateProfileResponseModel {
  final int? code;
  final String? message;
  final UpdateProfileDataModel? data;

  UpdateProfileResponseModel({this.code, this.message, this.data});

  bool get success => code == 200;

  factory UpdateProfileResponseModel.fromJson(Map<String, dynamic> json) {
    return UpdateProfileResponseModel(
      code: json['code'],
      message: json['message']?.toString(),
      data: json['data'] != null
          ? UpdateProfileDataModel.fromJson(json['data'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {'code': code, 'message': message, 'data': data?.toJson()};
  }
}

class UpdateProfileDataModel {
  final UserModel? personalData;
  final SocialDataModel? social;

  UpdateProfileDataModel({this.personalData, this.social});

  factory UpdateProfileDataModel.fromJson(Map<String, dynamic> json) {
    return UpdateProfileDataModel(
      personalData: json['personalData'] != null
          ? UserModel.fromJson(json['personalData'])
          : null,
      social: json['social'] != null
          ? SocialDataModel.fromJson(json['social'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {'personalData': personalData?.toJson(), 'social': social?.toJson()};
  }
}

class SocialDataModel {
  final int? id;
  final String? facebook;
  final String? twitter;
  final String? instagram;
  final String? linkedin;
  final String? youtube;
  final String? tiktok;
  final int? userId;
  final String? createdAt;
  final String? updatedAt;

  SocialDataModel({
    this.id,
    this.facebook,
    this.twitter,
    this.instagram,
    this.linkedin,
    this.youtube,
    this.tiktok,
    this.userId,
    this.createdAt,
    this.updatedAt,
  });

  factory SocialDataModel.fromJson(Map<String, dynamic> json) {
    return SocialDataModel(
      id: json['id'],
      facebook: json['facebook']?.toString(),
      twitter: json['twitter']?.toString(),
      instagram: json['instagram']?.toString(),
      linkedin: json['linkedin']?.toString(),
      youtube: json['youtube']?.toString(),
      tiktok: json['tiktok']?.toString(),
      userId: json['user_id'],
      createdAt: json['created_at']?.toString(),
      updatedAt: json['updated_at']?.toString(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'facebook': facebook,
      'twitter': twitter,
      'instagram': instagram,
      'linkedin': linkedin,
      'youtube': youtube,
      'tiktok': tiktok,
      'user_id': userId,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }
}
