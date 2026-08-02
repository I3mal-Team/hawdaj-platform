import 'package:hawdaj/features/tasneef/data/models/category_model.dart';
import 'package:hawdaj/features/home/data/model/places_model/gallery_model.dart';
import 'package:hawdaj/features/home/data/model/places_model/meta_model.dart';
import 'package:hawdaj/features/home/data/model/zad_model/rating_model.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';
import 'package:hawdaj/features/tasneef/data/models/rating_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';

class ZadModel {
  final int id;
  final String slug;
  final String type;
  final List<CategoryModel> categories;
  final List<CategoryModel> foodCategories;
  final String? address;
  final String image;
  final String menuFile;
  final String? facebookLink;
  final String? whatsapp;
  final String? instagramLink;
  //x_link
  final String? twitterLink;
  final String? websiteLink;
  final double lat;
  final double long;
  final bool featured;
  final bool visited;
  final int viewsNum;
  final double rate;
  final int reviewCount;
  final String title;
  final String description;
  final List<RatingModel> ratings;
  final List<GalleryModel> galleries;
  final CityModel city;
  final RegionModel region;
  final MetaModel? meta;
  final bool isFavorite;
  final bool isSaved;
  final String? ticketLink;

  // الحقول الإضافية من JSON
  final String? addressType;
  final String? distance;
  final bool isOnline;
  final List<dynamic>? relatedPlaces;
  final List<dynamic>? nearPlaces;
  final bool active;

  ZadModel({
    required this.id,
    required this.slug,
    required this.type,
    required this.categories,
    required this.foodCategories,
    required this.address,
    required this.image,
    required this.menuFile,
    required this.facebookLink,
    required this.whatsapp,
    required this.instagramLink,
    required this.websiteLink,
    required this.twitterLink,
    required this.lat,
    required this.long,
    required this.featured,
    required this.visited,
    required this.viewsNum,
    required this.rate,
    required this.reviewCount,
    required this.title,
    required this.description,
    required this.ratings,
    required this.galleries,
    required this.city,
    required this.region,
    required this.meta,
    required this.isFavorite,
    required this.isSaved,
    required this.addressType,
    required this.distance,
    required this.isOnline,
    required this.relatedPlaces,
    required this.nearPlaces,
    required this.active,
    this.ticketLink,
  });

  factory ZadModel.fromJson(Map<String, dynamic> json) {
    return ZadModel(
      id: (json['id'] as num?)?.toInt() ?? 0,
      slug: json['slug']?.toString() ?? '',
      type: json['type']?.toString() ?? '',
      categories:
          (json['categories'] as List<dynamic>?)
              ?.map((e) => CategoryModel.fromJson(e))
              .toList() ??
          [],
      foodCategories:
          (json['food_categories'] as List<dynamic>?)
              ?.map((e) => CategoryModel.fromJson(e))
              .toList() ??
          [],
      address: json['address']?.toString(),
      image: json['image']?.toString() ?? '',
      menuFile: json['menu_file']?.toString() ?? '',
      facebookLink: json['facebook_link']?.toString(),
      whatsapp: json['whatsapp']?.toString(),
      instagramLink: json['Instagram_link']?.toString(),
      websiteLink: json['website_link']?.toString(),
      twitterLink: json['x_link']?.toString(),
      lat: (json['lat'] as num?)?.toDouble() ?? 0.0,
      long: (json['long'] as num?)?.toDouble() ?? 0.0,
      featured: json['featured'] == true || json['featured'] == 1,
      visited: json['visited'] == true,
      viewsNum: (json['views_num'] as num?)?.toInt() ?? 0,
      rate: (json['rate'] as num?)?.toDouble() ?? 0.0,
      reviewCount: (json['review'] as num?)?.toInt() ?? 0,
      ticketLink: json['ticket_link']?.toString(),
      title: json['title']?.toString() ?? '',
      description: json['description']?.toString() ?? '',
      ratings:
          (json['ratings'] as List<dynamic>?)
              ?.map((e) => RatingModel.fromJson(e))
              .toList() ??
          [],
      galleries:
          (json['galleries'] as List<dynamic>?)
              ?.map((e) => GalleryModel.fromJson(e))
              .toList() ??
          [],
      city: CityModel.fromJson(json['city'] ?? {}),
      region: RegionModel.fromJson(json['region'] ?? {}),
      meta: json['meta'] != null ? MetaModel.fromJson(json['meta']) : null,
      isFavorite: json['is_favorite'] == true,
      isSaved: json['is_saved'] == true,

      // الحقول الجديدة
      addressType: json['address_type']?.toString(),
      distance: json['distance']?.toString(),
      isOnline: json['is_online'] == true,
      relatedPlaces: json['related_places'] as List<dynamic>?,
      nearPlaces: json['near_places'] as List<dynamic>?,
      active: json['active'] == true || json['active'] == 1,
    );
  }
}
