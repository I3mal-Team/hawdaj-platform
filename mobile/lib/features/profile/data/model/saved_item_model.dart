import 'package:hawdaj/features/exploration/data/model/glodal_search/city.dart';
import 'package:hawdaj/features/exploration/data/model/glodal_search/region.dart';

class SavedItemModel {
  final int id;
  final int savedId;
  final String savedSlug;
  final String savedType;
  final String savedImage;
  final String savedTitle;
  final String savedAddress;
  final double savedLat;
  final double savedLong;
  final double savedRatings;
  final bool savedFeatured;
  final Region? savedRegion;
  final City? savedCity;

  SavedItemModel({
    required this.id,
    required this.savedId,
    required this.savedSlug,
    required this.savedType,
    required this.savedImage,
    required this.savedTitle,
    required this.savedAddress,
    required this.savedLat,
    required this.savedLong,
    required this.savedRatings,
    required this.savedFeatured,
    this.savedRegion,
    this.savedCity,
  });

  factory SavedItemModel.fromJson(Map<String, dynamic> json) {
    return SavedItemModel(
      id: json['id'] ?? 0,
      savedId: json['saved_id'] ?? 0,
      savedSlug: json['saved_slug'] ?? '',
      savedType: json['saved_type'] ?? '',
      savedImage: json['saved_image'] ?? '',
      savedTitle: json['saved_title'] ?? '',
      savedAddress: json['saved_address'] ?? '',
      savedLat: (json['saved_lat'] ?? 0).toDouble(),
      savedLong: (json['saved_long'] ?? 0).toDouble(),
      savedRatings: (json['saved_ratings'] ?? 0).toDouble(),
      savedFeatured:
          json['saved_featured'] == 1 || json['saved_featured'] == true,
      savedRegion: json['saved_region'] != null
          ? Region.fromJson(json['saved_region'])
          : null,
      savedCity: json['saved_city'] != null
          ? City.fromJson(json['saved_city'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'saved_id': savedId,
      'saved_slug': savedSlug,
      'saved_type': savedType,
      'saved_image': savedImage,
      'saved_title': savedTitle,
      'saved_address': savedAddress,
      'saved_lat': savedLat,
      'saved_long': savedLong,
      'saved_ratings': savedRatings,
      'saved_featured': savedFeatured,
      'saved_region': savedRegion?.toJson(),
      'saved_city': savedCity?.toJson(),
    };
  }
}
