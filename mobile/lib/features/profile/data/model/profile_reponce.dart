import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/auth/data/models/user_model.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';
import 'package:hawdaj/features/trip/data/model/my_trip_model.dart';

class ProfilePageResponse extends Equatable {
  final ProfileData data;

  const ProfilePageResponse({required this.data});

  factory ProfilePageResponse.fromJson(Map<String, dynamic> json) {
    return ProfilePageResponse(
      data: ProfileData.fromJson(json['data'] ?? const {}),
    );
  }

  Map<String, dynamic> toJson() => {'data': data.toJson()};

  @override
  List<Object?> get props => [data];
}

/// محتوى "data"
class ProfileData extends Equatable {
  final UserModel personalData; // "PersonalData"
  final GuideModel? userGuideData; // "userGuideData"
  final List<UserSocial> social; // "social" (array)
  final bool hasSocial; // "hasSocial"
  final MyStoriesPage myStories; // "myStories"
  final MyTripsPage myTrips; // "myTrips"
  final int totalStories; // "totalStories"
  final int totalTrips; // "totalTrips"

  const ProfileData({
    required this.personalData,
    required this.userGuideData,
    required this.social,
    required this.hasSocial,
    required this.myStories,
    required this.myTrips,
    required this.totalStories,
    required this.totalTrips,
  });

  factory ProfileData.fromJson(Map<String, dynamic> json) {
    return ProfileData(
      personalData: UserModel.fromJson(json['PersonalData'] ?? const {}),
      userGuideData: json['userGuideData'] != null
          ? GuideModel.fromJson(json['userGuideData'])
          : null,
      social: (json['social'] as List? ?? const [])
          .map((e) => UserSocial.fromJson(e ?? const {}))
          .toList(),
      hasSocial: (json['hasSocial'] ?? false) as bool,
      myStories: MyStoriesPage.fromJson(json['myStories'] ?? const {}),
      myTrips: MyTripsPage.fromJson(json['myTrips'] ?? const {}),
      totalStories: _toInt(json['totalStories']),
      totalTrips: _toInt(json['totalTrips']),
    );
  }

  Map<String, dynamic> toJson() => {
    'PersonalData': personalData.toJson(),
    'userGuideData': userGuideData, // لو GuideModel فيه toJson() هيشتغل
    'social': social.map((e) => e.toJson()).toList(),
    'hasSocial': hasSocial,
    'myStories': myStories.toJson(),
    'myTrips': myTrips.toJson(),
    'totalStories': totalStories,
    'totalTrips': totalTrips,
  };

  @override
  List<Object?> get props => [
    personalData,
    userGuideData,
    social,
    hasSocial,
    myStories,
    myTrips,
    totalStories,
    totalTrips,
  ];
}

/// بيانات المستخدم الشخصية

/// عنصر السوشيال (اللي جوه مصفوفة "social")
class UserSocial extends Equatable {
  final int id;
  final String? facebook;
  final String? twitter;
  final String? instagram;
  final String? linkedin;
  final String? youtube;
  final String? tiktok;
  final int userId;
  final String? createdAt; // ISO string
  final String? updatedAt;

  const UserSocial({
    required this.id,
    required this.facebook,
    required this.twitter,
    required this.instagram,
    required this.linkedin,
    required this.youtube,
    required this.tiktok,
    required this.userId,
    required this.createdAt,
    required this.updatedAt,
  });

  factory UserSocial.fromJson(Map<String, dynamic> json) {
    return UserSocial(
      id: _toInt(json['id']),
      facebook: _toStrOrNull(json['facebook']),
      twitter: _toStrOrNull(json['twitter']),
      instagram: _toStrOrNull(json['instagram']),
      linkedin: _toStrOrNull(json['linkedin']),
      youtube: _toStrOrNull(json['youtube']),
      tiktok: _toStrOrNull(json['tiktok']),
      userId: _toInt(json['user_id']),
      createdAt: _toStrOrNull(json['created_at']),
      updatedAt: _toStrOrNull(json['updated_at']),
    );
  }

  Map<String, dynamic> toJson() => {
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

  @override
  List<Object?> get props => [
    id,
    facebook,
    twitter,
    instagram,
    linkedin,
    youtube,
    tiktok,
    userId,
    createdAt,
    updatedAt,
  ];
}

/// صفحة القصص (مع العناصر + بيانات التصفح)
class MyStoriesPage extends Equatable {
  final int currentPage;
  final List<StoryItem> items;
  final String? firstPageUrl;
  final int? from;
  final int lastPage;
  final String? lastPageUrl;
  final String? nextPageUrl;
  final String? path;
  final int perPage;
  final String? prevPageUrl;
  final int? to;
  final int total;

  const MyStoriesPage({
    required this.currentPage,
    required this.items,
    required this.firstPageUrl,
    required this.from,
    required this.lastPage,
    required this.lastPageUrl,
    required this.nextPageUrl,
    required this.path,
    required this.perPage,
    required this.prevPageUrl,
    required this.to,
    required this.total,
  });

  factory MyStoriesPage.fromJson(Map<String, dynamic> json) {
    return MyStoriesPage(
      currentPage: _toInt(json['current_page']),
      items: (json['items'] as List? ?? const [])
          .map((e) => StoryItem.fromJson(e ?? const {}))
          .toList(),
      firstPageUrl: _toStrOrNull(json['first_page_url']),
      from: _toIntOrNull(json['from']),
      lastPage: _toInt(json['last_page']),
      lastPageUrl: _toStrOrNull(json['last_page_url']),
      nextPageUrl: _toStrOrNull(json['next_page_url']),
      path: _toStrOrNull(json['path']),
      perPage: _toInt(json['per_page']),
      prevPageUrl: _toStrOrNull(json['prev_page_url']),
      to: _toIntOrNull(json['to']),
      total: _toInt(json['total']),
    );
  }

  Map<String, dynamic> toJson() => {
    'current_page': currentPage,
    'items': items.map((e) => e.toJson()).toList(),
    'first_page_url': firstPageUrl,
    'from': from,
    'last_page': lastPage,
    'last_page_url': lastPageUrl,
    'next_page_url': nextPageUrl,
    'path': path,
    'per_page': perPage,
    'prev_page_url': prevPageUrl,
    'to': to,
    'total': total,
  };

  @override
  List<Object?> get props => [
    currentPage,
    items,
    firstPageUrl,
    from,
    lastPage,
    lastPageUrl,
    nextPageUrl,
    path,
    perPage,
    prevPageUrl,
    to,
    total,
  ];
}

/// عنصر قصة
class StoryItem extends Equatable {
  final int id;
  final String type; // "file" مثلاً
  final String? text;
  final String file; // URL للصورة/الفيديو
  final String status; // "active"
  final int totalViews;
  final int totalLikes;
  final int totalComments;
  final int totalShares;

  const StoryItem({
    required this.id,
    required this.type,
    required this.text,
    required this.file,
    required this.status,
    required this.totalViews,
    required this.totalLikes,
    required this.totalComments,
    required this.totalShares,
  });

  factory StoryItem.fromJson(Map<String, dynamic> json) {
    return StoryItem(
      id: _toInt(json['id']),
      type: (json['type'] ?? '') as String,
      text: _toStrOrNull(json['text']),
      file: (json['file'] ?? '') as String,
      status: (json['status'] ?? '') as String,
      totalViews: _toInt(json['total_views']),
      totalLikes: _toInt(json['total_likes']),
      totalComments: _toInt(json['total_comments']),
      totalShares: _toInt(json['total_shares']),
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'type': type,
    'text': text,
    'file': file,
    'status': status,
    'total_views': totalViews,
    'total_likes': totalLikes,
    'total_comments': totalComments,
    'total_shares': totalShares,
  };

  @override
  List<Object?> get props => [
    id,
    type,
    text,
    file,
    status,
    totalViews,
    totalLikes,
    totalComments,
    totalShares,
  ];
}

/// صفحة الرحلات (مع العناصر + بيانات التصفح)
class MyTripsPage extends Equatable {
  final int currentPage;
  final List<MyTripModel> items;
  final String? firstPageUrl;
  final int? from;
  final int lastPage;
  final String? lastPageUrl;
  final String? nextPageUrl;
  final String? path;
  final int perPage;
  final String? prevPageUrl;
  final int? to;
  final int total;

  const MyTripsPage({
    required this.currentPage,
    required this.items,
    required this.firstPageUrl,
    required this.from,
    required this.lastPage,
    required this.lastPageUrl,
    required this.nextPageUrl,
    required this.path,
    required this.perPage,
    required this.prevPageUrl,
    required this.to,
    required this.total,
  });

  factory MyTripsPage.fromJson(Map<String, dynamic> json) {
    return MyTripsPage(
      currentPage: _toInt(json['current_page']),
      items: (json['items'] as List? ?? const [])
          .map((e) => MyTripModel.fromJson(e ?? const {}))
          .toList(),
      firstPageUrl: _toStrOrNull(json['first_page_url']),
      from: _toIntOrNull(json['from']),
      lastPage: _toInt(json['last_page']),
      lastPageUrl: _toStrOrNull(json['last_page_url']),
      nextPageUrl: _toStrOrNull(json['next_page_url']),
      path: _toStrOrNull(json['path']),
      perPage: _toInt(json['per_page']),
      prevPageUrl: _toStrOrNull(json['prev_page_url']),
      to: _toIntOrNull(json['to']),
      total: _toInt(json['total']),
    );
  }

  Map<String, dynamic> toJson() => {
    'current_page': currentPage,
    'items': items.map((e) => e.toJson()).toList(),
    'first_page_url': firstPageUrl,
    'from': from,
    'last_page': lastPage,
    'last_page_url': lastPageUrl,
    'next_page_url': nextPageUrl,
    'path': path,
    'per_page': perPage,
    'prev_page_url': prevPageUrl,
    'to': to,
    'total': total,
  };

  @override
  List<Object?> get props => [
    currentPage,
    items,
    firstPageUrl,
    from,
    lastPage,
    lastPageUrl,
    nextPageUrl,
    path,
    perPage,
    prevPageUrl,
    to,
    total,
  ];
}

/// عنصر رحلة

/// Helpers
int _toInt(dynamic v, [int def = 0]) {
  if (v == null) return def;
  if (v is int) return v;
  if (v is double) return v.toInt();
  if (v is String) return int.tryParse(v) ?? def;
  return def;
}

int? _toIntOrNull(dynamic v) {
  if (v == null) return null;
  if (v is int) return v;
  if (v is double) return v.toInt();
  if (v is String) return int.tryParse(v);
  return null;
}

String? _toStrOrNull(dynamic v) {
  if (v == null) return null;
  return v.toString();
}
