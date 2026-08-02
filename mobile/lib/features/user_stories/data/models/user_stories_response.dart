import 'package:hawdaj/features/home/data/model/paginated_response.dart';
import 'package:hawdaj/features/user_stories/data/models/user_story_model.dart';

class UserStoriesResponse {
  final int code;
  final String message;
  final ProfilePageData data;

  UserStoriesResponse({
    required this.code,
    required this.message,
    required this.data,
  });

  factory UserStoriesResponse.fromJson(Map<String, dynamic> json) {
    return UserStoriesResponse(
      code: json['code']?.toInt() ?? 0,
      message: json['message']?.toString() ?? '',
      data: ProfilePageData.fromJson(json['data'] ?? {}),
    );
  }

  // Getter to easily access stories
  PaginatedResponse<UserStoryModel>? get stories => data.myStories;

  Map<String, dynamic> toJson() {
    return {
      'code': code,
      'message': message,
      'data': data.toJson(),
    };
  }

  @override
  String toString() {
    return 'UserStoriesResponse(code: $code, message: $message, data: $data)';
  }
}

class ProfilePageData {
  final PersonalData? personalData;
  final dynamic userGuideData;
  final List<dynamic> social;
  final bool hasSocial;
  final PaginatedResponse<UserStoryModel>? myStories;
  final PaginatedResponse<dynamic>? myTrips;
  final int totalStories;
  final int totalTrips;

  ProfilePageData({
    this.personalData,
    this.userGuideData,
    required this.social,
    required this.hasSocial,
    this.myStories,
    this.myTrips,
    required this.totalStories,
    required this.totalTrips,
  });

  factory ProfilePageData.fromJson(Map<String, dynamic> json) {
    return ProfilePageData(
      personalData: json['PersonalData'] != null 
          ? PersonalData.fromJson(json['PersonalData']) 
          : null,
      userGuideData: json['userGuideData'],
      social: json['social']?.cast<dynamic>() ?? [],
      hasSocial: json['hasSocial'] ?? false,
      myStories: json['myStories'] != null 
          ? PaginatedResponse.fromJson(
              json['myStories'],
              (itemJson) => UserStoryModel.fromJson(itemJson),
            )
          : null,
      myTrips: json['myTrips'] != null 
          ? PaginatedResponse.fromJson(
              json['myTrips'],
              (itemJson) => itemJson,
            )
          : null,
      totalStories: json['totalStories']?.toInt() ?? 0,
      totalTrips: json['totalTrips']?.toInt() ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'PersonalData': personalData?.toJson(),
      'userGuideData': userGuideData,
      'social': social,
      'hasSocial': hasSocial,
      'myStories': myStories?.toJson((story) => story.toJson()),
      'myTrips': myTrips?.toJson((trip) => trip),
      'totalStories': totalStories,
      'totalTrips': totalTrips,
    };
  }
}

class PersonalData {
  final int id;
  final String firstName;
  final String lastName;
  final String email;
  final String? phone;
  final String? photo;
  final String gender;
  final String fullName;
  final int totalPoints;

  PersonalData({
    required this.id,
    required this.firstName,
    required this.lastName,
    required this.email,
    this.phone,
    this.photo,
    required this.gender,
    required this.fullName,
    required this.totalPoints,
  });

  factory PersonalData.fromJson(Map<String, dynamic> json) {
    return PersonalData(
      id: json['id']?.toInt() ?? 0,
      firstName: json['first_name']?.toString() ?? '',
      lastName: json['last_name']?.toString() ?? '',
      email: json['email']?.toString() ?? '',
      phone: json['phone']?.toString(),
      photo: json['photo']?.toString(),
      gender: json['gender']?.toString() ?? '',
      fullName: json['full_name']?.toString() ?? '',
      totalPoints: json['total_points']?.toInt() ?? 0,
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
