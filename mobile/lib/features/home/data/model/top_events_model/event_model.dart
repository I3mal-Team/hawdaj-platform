import 'package:hawdaj/features/home/data/model/places_model/gallery_model.dart';
import 'package:hawdaj/features/home/data/model/places_model/meta_model.dart';
import 'package:hawdaj/features/home/data/model/zad_model/rating_model.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';
import 'package:hawdaj/features/tasneef/data/models/rating_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';

class EventModel {
  final int id;
  final String slug;
  final String type;
  final String title;
  final String description;
  final String? address;
  final String? image;
  final double lat;
  final String facebook;
  final String whatsapp;
  final String instagram;
  final String linkedin;
  final double long;
  final String fromDate;
  final String toDate;
  final bool isFavorite;

  final bool isSaved;
  final bool closed;
  final String? ticketLink;
  final String? displayType;
  final int viewsNum;
  final int? rate;
  final int featured;
  final CityModel? city;
  final String? website;
  final RegionModel? region;
  final MetaModel? meta;
  final List<GalleryModel> galleries;
  final List<RatingModel> ratings;

  EventModel({
    required this.id,
    required this.slug,
    required this.type,
    required this.title,
    required this.description,
    this.address,
    this.image,
    this.lat = 0.0,
    this.long = 0.0,
    this.fromDate = '',
    this.toDate = '',
    this.isFavorite = false,
    this.isSaved = false,
    this.closed = false,
    this.facebook = '',
    this.whatsapp = '',
    this.instagram = '',
    this.linkedin = '',
    this.ticketLink,
    this.displayType,
    this.viewsNum = 0,
    this.website,
    this.rate,
    this.featured = 0,
    this.city,
    this.region,
    this.meta,
    this.galleries = const [],
    this.ratings = const [],
  });

  factory EventModel.fromJson(Map<String, dynamic> json) {
    return EventModel(
      id: json['id'] ?? 0,
      slug: json['slug'] ?? '',
      type: json['type'] ?? '',
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      address: json['address'],
      image: json['image'],
      facebook: json['facebook'] ?? '',
      whatsapp: json['whatsapp'] ?? '',
      instagram: json['instagram'] ?? '',
      linkedin: json['linkedin'] ?? '',
      lat: (json['lat'] as num?)?.toDouble() ?? 0.0,
      long: (json['long'] as num?)?.toDouble() ?? 0.0,
      fromDate: json['date_from'] ?? '',
      toDate: json['date_to'] ?? '',
      isFavorite: json['is_favorite'] ?? false,
      website: json['website'] ?? '',
      isSaved: json['is_saved'] ?? false,
      closed: json['closed'] ?? false,
      ticketLink: json['ticket_link'],
      displayType: json['display_type'],
      viewsNum: json['views_num'] ?? 0,
      rate: json['rate'] != null ? int.tryParse(json['rate'].toString()) : null,
      featured: json['featured'] ?? 0,
      city: json['city'] != null ? CityModel.fromJson(json['city']) : null,
      region: json['region'] != null
          ? RegionModel.fromJson(json['region'])
          : null,
      meta: json['meta'] != null ? MetaModel.fromJson(json['meta']) : null,
      galleries:
          (json['galleries'] as List<dynamic>?)
              ?.map((e) => GalleryModel.fromJson(e))
              .toList() ??
          [],
      ratings:
          (json['ratings'] as List<dynamic>?)
              ?.map((e) => RatingModel.fromJson(e))
              .toList() ??
          [],
    );
  }
}
