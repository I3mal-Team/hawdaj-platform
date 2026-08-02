import 'package:json_annotation/json_annotation.dart';

part 'list_landmark_response.g.dart';

@JsonSerializable()
class LandmarkResponse {
  final int code;
  final String message;
  final LandmarkData data;

  LandmarkResponse({
    required this.code,
    required this.message,
    required this.data,
  });

  factory LandmarkResponse.fromJson(Map<String, dynamic> json) =>
      _$LandmarkResponseFromJson(json);
  Map<String, dynamic> toJson() => _$LandmarkResponseToJson(this);
}

@JsonSerializable()
class LandmarkData {
  @JsonKey(name: 'current_page')
  final int currentPage;
  final List<LandmarkItem> items;
  @JsonKey(name: 'first_page_url')
  final String firstPageUrl;
  final int from;
  @JsonKey(name: 'last_page')
  final int lastPage;
  @JsonKey(name: 'last_page_url')
  final String lastPageUrl;
  @JsonKey(name: 'next_page_url')
  final String? nextPageUrl;
  final String path;
  @JsonKey(name: 'per_page')
  final int perPage;
  @JsonKey(name: 'prev_page_url')
  final String? prevPageUrl;
  final int to;
  final int total;

  LandmarkData({
    required this.currentPage,
    required this.items,
    required this.firstPageUrl,
    required this.from,
    required this.lastPage,
    required this.lastPageUrl,
    this.nextPageUrl,
    required this.path,
    required this.perPage,
    this.prevPageUrl,
    required this.to,
    required this.total,
  });

  factory LandmarkData.fromJson(Map<String, dynamic> json) =>
      _$LandmarkDataFromJson(json);
  Map<String, dynamic> toJson() => _$LandmarkDataToJson(this);
}

@JsonSerializable()
class LandmarkItem {
  final int id;
  final String title;
  final String description;
  final String address;
  final String image;

  LandmarkItem({
    required this.id,
    required this.title,
    required this.description,
    required this.address,
    required this.image,
  });

  factory LandmarkItem.fromJson(Map<String, dynamic> json) =>
      _$LandmarkItemFromJson(json);
  Map<String, dynamic> toJson() => _$LandmarkItemToJson(this);
}
