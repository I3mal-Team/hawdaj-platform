// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'list_landmark_response.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

LandmarkResponse _$LandmarkResponseFromJson(Map<String, dynamic> json) =>
    LandmarkResponse(
      code: (json['code'] as num).toInt(),
      message: json['message'] as String,
      data: LandmarkData.fromJson(json['data'] as Map<String, dynamic>),
    );

Map<String, dynamic> _$LandmarkResponseToJson(LandmarkResponse instance) =>
    <String, dynamic>{
      'code': instance.code,
      'message': instance.message,
      'data': instance.data,
    };

LandmarkData _$LandmarkDataFromJson(Map<String, dynamic> json) => LandmarkData(
  currentPage: (json['current_page'] as num).toInt(),
  items: (json['items'] as List<dynamic>)
      .map((e) => LandmarkItem.fromJson(e as Map<String, dynamic>))
      .toList(),
  firstPageUrl: json['first_page_url'] as String,
  from: (json['from'] as num).toInt(),
  lastPage: (json['last_page'] as num).toInt(),
  lastPageUrl: json['last_page_url'] as String,
  nextPageUrl: json['next_page_url'] as String?,
  path: json['path'] as String,
  perPage: (json['per_page'] as num).toInt(),
  prevPageUrl: json['prev_page_url'] as String?,
  to: (json['to'] as num).toInt(),
  total: (json['total'] as num).toInt(),
);

Map<String, dynamic> _$LandmarkDataToJson(LandmarkData instance) =>
    <String, dynamic>{
      'current_page': instance.currentPage,
      'items': instance.items,
      'first_page_url': instance.firstPageUrl,
      'from': instance.from,
      'last_page': instance.lastPage,
      'last_page_url': instance.lastPageUrl,
      'next_page_url': instance.nextPageUrl,
      'path': instance.path,
      'per_page': instance.perPage,
      'prev_page_url': instance.prevPageUrl,
      'to': instance.to,
      'total': instance.total,
    };

LandmarkItem _$LandmarkItemFromJson(Map<String, dynamic> json) => LandmarkItem(
  id: (json['id'] as num).toInt(),
  title: json['title'] as String,
  description: json['description'] as String,
  address: json['address'] as String,
  image: json['image'] as String,
);

Map<String, dynamic> _$LandmarkItemToJson(LandmarkItem instance) =>
    <String, dynamic>{
      'id': instance.id,
      'title': instance.title,
      'description': instance.description,
      'address': instance.address,
      'image': instance.image,
    };
