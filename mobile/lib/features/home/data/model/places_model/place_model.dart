// place_model.dart
import 'package:hawdaj/features/tasneef/data/models/category_model.dart';
import 'package:hawdaj/features/home/data/model/places_model/gallery_model.dart';
import 'package:hawdaj/features/home/data/model/places_model/meta_model.dart';
import 'package:hawdaj/features/home/data/model/places_model/price_model.dart';
import 'package:hawdaj/features/home/data/model/zad_model/rating_model.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';
import 'package:hawdaj/features/tasneef/data/models/rating_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';

class PlaceModel {
  final int id;
  final String slug;
  final String type;
  final String? address;
  final String image;
  final String coverImage;
  final num? rate;
  final num? review;
  final List<RatingModel> ratings;

  final String? ticketLink;
  final String? websiteLink;
  final String? instagramLink;
  final String? whatsapp;
  final String? facebookLink;
  final num? temperature;
  final List<String> seasons;
  final List<CategoryModel> categories;
  final List<GalleryModel> galleries;
  final PriceModel? price;
  final CityModel city;
  final RegionModel region;
  final String addressType;
  final int viewsNum;
  final bool featured;
  final double lat;
  final double long;
  final bool visited;
  final String? distance;
  final String title;
  final String description;
  final MetaModel? meta;
  final bool isFavorite;
  final bool isSaved;

  PlaceModel({
    this.review,
    this.rate,
    required this.id,
    required this.slug,
    required this.type,
    required this.categories,
    required this.address,
    required this.image,
    required this.coverImage,
    required this.ticketLink,
    required this.websiteLink,
    required this.instagramLink,
    required this.whatsapp,
    required this.facebookLink,
    required this.temperature,
    required this.seasons,
    required this.galleries,
    required this.price,
    required this.city,
    required this.ratings,
    required this.region,
    required this.addressType,
    required this.viewsNum,
    required this.featured,
    required this.lat,
    required this.long,
    required this.visited,
    required this.distance,
    required this.title,
    required this.description,
    required this.meta,
    required this.isFavorite,
    required this.isSaved,
  });

  /// Updated: resilient parsing.
  /// - Accepts either a full Map or an `int` (ID only).
  /// - Handles null/primitive values safely for lists and numbers.
  factory PlaceModel.fromJson(dynamic source) {
    // Case 1: backend returned only an ID
    if (source is int) {
      return PlaceModel(
        id: source,
        slug: '',
        type: '',
        categories: const [],
        address: null,
        image: '',
        coverImage: '',
        ticketLink: null,
        websiteLink: null,
        instagramLink: null,
        whatsapp: null,
        facebookLink: null,
        temperature: null,
        seasons: const [],
        galleries: const [],
        price: null,
        city: CityModel(id: 0, name: ''),
        ratings: const [],
        region: RegionModel(id: 0, name: ''),
        addressType: '',
        viewsNum: 0,
        featured: false,
        lat: 0.0,
        long: 0.0,
        visited: false,
        distance: null,
        title: '',
        description: '',
        meta: null,
        isFavorite: false,
        isSaved: false,
        rate: null,
        review: null,
      );
    }

    // Case 2: normal Map
    if (source is! Map<String, dynamic>) {
      throw FormatException(
        'PlaceModel.fromJson expects Map or int, got ${source.runtimeType}',
        source,
      );
    }
    final Map<String, dynamic> json = source;

    final List<RatingModel> ratings = _toList(
      json['ratings'],
    ).map((e) => RatingModel.fromJson(e)).toList();

    final List<CategoryModel> categories = _toList(
      json['categories'],
    ).map((e) => CategoryModel.fromJson(e)).toList();

    final List<GalleryModel> galleries = _toList(
      json['galleries'],
    ).map((e) => GalleryModel.fromJson(e)).toList();

    final List<String> seasons = _toList(
      json['seasons'],
    ).map((e) => e?.toString() ?? '').where((e) => e.isNotEmpty).toList();

    return PlaceModel(
      ratings: ratings,
      id: _asInt(json['id']),
      slug: _asString(json['slug']),
      rate: _asNumOrNull(json['rate']),
      review: _asNumOrNull(json['review']),
      type: _asString(json['type']),
      categories: categories,
      address: _asNullableString(json['address']),
      image: _asString(json['image']),
      coverImage: _asString(
        json['cover_image'],
        fallback: _asString(json['image']),
      ),
      ticketLink: _asNullableString(json['ticket_link']),
      websiteLink: _asNullableString(json['website_link']),
      instagramLink: _asNullableString(json['instagram_link']),
      whatsapp: _asNullableString(json['whatsapp']),
      facebookLink: _asNullableString(json['facebook_link']),
      temperature: _asNumOrNull(json['temperature']),
      seasons: seasons,
      galleries: galleries,
      price: json['price'] != null ? PriceModel.fromJson(json['price']) : null,
      city: json['city'] != null
          ? CityModel.fromJson(json['city'])
          : CityModel(id: 0, name: ''),
      region: json['region'] != null
          ? RegionModel.fromJson(json['region'])
          : RegionModel(id: 0, name: ''),
      addressType: _asString(json['address_type']),
      viewsNum: _asInt(json['views_num']),
      featured: _asBool(json['featured']),
      lat: _asDouble(json['lat']),
      long: _asDouble(json['long']),
      visited: _asBool(json['visited']),
      distance: _asNullableString(json['distance']),
      title: _asString(json['title']),
      description: _asString(json['description']),
      meta: json['meta'] != null ? MetaModel.fromJson(json['meta']) : null,
      isFavorite: _asBool(json['is_favorite']),
      isSaved: _asBool(json['is_saved']),
    );
  }

  static List<dynamic> _toList(dynamic v) {
    if (v == null) return const [];
    if (v is List) return v;
    // allow single element (e.g., server returns an object/string instead of list)
    return [v];
  }

  static int _asInt(dynamic v, {int fallback = 0}) {
    if (v == null) return fallback;
    if (v is int) return v;
    if (v is num) return v.toInt();
    if (v is String) return int.tryParse(v) ?? fallback;
    return fallback;
  }

  static double _asDouble(dynamic v, {double fallback = 0.0}) {
    if (v == null) return fallback;
    if (v is double) return v;
    if (v is int) return v.toDouble();
    if (v is num) return v.toDouble();
    if (v is String) return double.tryParse(v) ?? fallback;
    return fallback;
  }

  static num? _asNumOrNull(dynamic v) {
    if (v == null) return null;
    if (v is num) return v;
    if (v is String) return num.tryParse(v);
    return null;
  }

  static bool _asBool(dynamic v, {bool fallback = false}) {
    if (v == null) return fallback;
    if (v is bool) return v;
    if (v is int) return v != 0;
    if (v is String) {
      final s = v.toLowerCase().trim();
      if (s == 'true' || s == '1' || s == 'yes') return true;
      if (s == 'false' || s == '0' || s == 'no') return false;
    }
    return fallback;
  }

  static String _asString(dynamic v, {String fallback = ''}) {
    if (v == null) return fallback;
    return v.toString();
  }

  static String? _asNullableString(dynamic v) {
    if (v == null) return null;
    final s = v.toString();
    return s.isEmpty ? null : s;
  }
}
