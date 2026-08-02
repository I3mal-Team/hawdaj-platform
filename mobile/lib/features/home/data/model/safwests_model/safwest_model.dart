import 'package:hawdaj/features/tasneef/data/models/category_model.dart';
import 'package:hawdaj/features/home/data/model/places_model/gallery_model.dart';

class SafwestModel {
  final int id;
  final String slug;
  final String image;
  final String? coverImage;
  final String type;
  final bool active;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final bool featured;
  final String? address;
  final String? audioStoryLink;
  final List<String> mainCharacters;
  final List<CategoryModel> categories;
  final bool showInHome;
  final double rate;
  final int review;
  final String title;
  final String description;
  final String content;
  final String timePeriod;
  final String location;
  final String storyImportance;
  final String relevanceToPresent;
  final String? source;
  final String? audioStoryTitle;
  final List<Translation> translations;
  final bool isFavorite;
  final bool isSaved;
  final List<GalleryModel> galleries;

  SafwestModel({
    required this.id,
    required this.slug,
    required this.image,
    required this.coverImage,
    required this.type,
    required this.active,
    required this.createdAt,
    required this.updatedAt,
    required this.featured,
    required this.address,
    required this.audioStoryLink,
    required this.mainCharacters,
    required this.categories,
    required this.showInHome,
    required this.rate,
    required this.review,
    required this.title,
    required this.description,
    required this.content,
    required this.timePeriod,
    required this.location,
    required this.storyImportance,
    required this.relevanceToPresent,
    required this.source,
    required this.audioStoryTitle,
    required this.translations,
    required this.isFavorite,
    required this.isSaved,
    required this.galleries,
  });

  factory SafwestModel.fromJson(Map<String, dynamic> json) {
    return SafwestModel(
      id: (json['id'] as num?)?.toInt() ?? 0,
      slug: json['slug']?.toString() ?? '',
      image: json['image']?.toString() ?? '',
      coverImage: json['cover_image']?.toString(),
      type: json['type']?.toString() ?? '',
      active: json['active'] == 1 || json['active'] == true,
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.tryParse(json['updated_at'])
          : null,
      featured: json['featured'] == 1 || json['featured'] == true,
      address: json['address']?.toString(),
      audioStoryLink: json['audioStoryLink']?.toString(),
      mainCharacters:
          (json['mainCharacters'] as List?)
              ?.map((e) => e.toString())
              .toList() ??
          [],
      categories:
          (json['categories'] as List?)
              ?.map((e) => CategoryModel.fromJson(e))
              .toList() ??
          [],
      showInHome: json['show_in_home'] == true,
      rate: (json['rate'] as num?)?.toDouble() ?? 0.0,
      review: (json['review'] as num?)?.toInt() ?? 0,
      title: json['title']?.toString() ?? '',
      description: json['description']?.toString() ?? '',
      content: json['content']?.toString() ?? '',
      timePeriod: json['timePeriod']?.toString() ?? '',
      location: json['location']?.toString() ?? '',
      storyImportance: json['storyImportance']?.toString() ?? '',
      relevanceToPresent: json['relevanceToPresent']?.toString() ?? '',
      source: json['source']?.toString(),
      audioStoryTitle: json['audioStoryTitle']?.toString(),
      translations:
          (json['translations'] as List?)
              ?.map((e) => Translation.fromJson(e))
              .toList() ??
          [],
      isFavorite: json['is_favorite'] == true,
      isSaved: json['is_saved'] == true,
      galleries:
          (json['galleries'] as List?)
              ?.map((e) => GalleryModel.fromJson(e))
              .toList() ??
          [],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'slug': slug,
      'image': image,
      'cover_image': coverImage,
      'type': type,
      'active': active,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
      'featured': featured,
      'address': address,
      'audioStoryLink': audioStoryLink,
      'mainCharacters': mainCharacters,
      'categories': categories,
      'show_in_home': showInHome,
      'rate': rate,
      'review': review,
      'title': title,
      'description': description,
      'content': content,
      'timePeriod': timePeriod,
      'location': location,
      'storyImportance': storyImportance,
      'relevanceToPresent': relevanceToPresent,
      'source': source,
      'audioStoryTitle': audioStoryTitle,
      'translations': translations.map((t) => t.toJson()).toList(),
      'is_favorite': isFavorite,
      'is_saved': isSaved,
      'galleries': galleries,
    };
  }
}

class Translation {
  final int id;
  final int swalefId;
  final String locale;
  final String title;
  final String description;
  final String content;
  final String timePeriod;
  final String location;
  final String storyImportance;
  final String relevanceToPresent;
  final String? source;
  final String? audioStoryTitle;

  Translation({
    required this.id,
    required this.swalefId,
    required this.locale,
    required this.title,
    required this.description,
    required this.content,
    required this.timePeriod,
    required this.location,
    required this.storyImportance,
    required this.relevanceToPresent,
    required this.source,
    required this.audioStoryTitle,
  });

  factory Translation.fromJson(Map<String, dynamic> json) {
    return Translation(
      id: (json['id'] as num?)?.toInt() ?? 0,
      swalefId: (json['swalef_id'] as num?)?.toInt() ?? 0,
      locale: json['locale']?.toString() ?? '',
      title: json['title']?.toString() ?? '',
      description: json['description']?.toString() ?? '',
      content: json['content']?.toString() ?? '',
      timePeriod: json['timePeriod']?.toString() ?? '',
      location: json['location']?.toString() ?? '',
      storyImportance: json['storyImportance']?.toString() ?? '',
      relevanceToPresent: json['relevanceToPresent']?.toString() ?? '',
      source: json['source']?.toString(),
      audioStoryTitle: json['audioStoryTitle']?.toString(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'swalef_id': swalefId,
      'locale': locale,
      'title': title,
      'description': description,
      'content': content,
      'timePeriod': timePeriod,
      'location': location,
      'storyImportance': storyImportance,
      'relevanceToPresent': relevanceToPresent,
      'source': source,
      'audioStoryTitle': audioStoryTitle,
    };
  }
}
