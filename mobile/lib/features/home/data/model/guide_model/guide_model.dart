// guide_model.dart
import 'package:hawdaj/features/home/data/model/guide_model/language_model.dart';
import 'package:hawdaj/features/home/data/model/guide_model/social_model.dart';
import 'package:hawdaj/features/home/data/model/places_model/gallery_model.dart';
import 'package:hawdaj/features/home/data/model/zad_model/rating_model.dart';
import 'package:hawdaj/features/tasneef/data/models/rating_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';

class GuideModel {
  final int id;
  final String type;
  final String name;
  final String nickName;
  final String description;
  final String image;
  final bool showInHome;
  final int experience;
  final String gender;
  final List<RegionModel> regions;
  final List<LanguageModel> languages;
  final SocialModel social;
  final int rate;
  final List<RatingModel> ratings;
  final List<GalleryModel> galleries;

  GuideModel({
    required this.id,
    required this.type,
    required this.name,
    required this.nickName,
    required this.description,
    required this.image,
    required this.showInHome,
    required this.experience,
    required this.gender,
    required this.regions,
    required this.languages,
    required this.social,
    required this.rate,
    required this.ratings,
    required this.galleries,
  });

  factory GuideModel.fromJson(Map<String, dynamic> json) {
    return GuideModel(
      id: json['id'] ?? 0,
      type: json['type'] ?? '',
      name: json['name'] ?? '',
      nickName: json['nickName'] ?? '',
      description: json['description'] ?? '',
      image: json['image'] ?? '',
      // 👇 الحل هنا
      showInHome:
          json['show_in_home'] == true ||
          json['show_in_home'] == 1 ||
          json['show_in_home'] == '1',
      experience: json['experience'] ?? 0,
      gender: json['gender'] ?? '',
      regions: (json['regions'] as List? ?? [])
          .map((e) => RegionModel.fromJson(e))
          .toList(),
      languages: (json['languages'] as List? ?? [])
          .map((e) => LanguageModel.fromJson(e))
          .toList(),
      social: json['social'] != null
          ? SocialModel.fromJson(json['social'])
          : SocialModel.empty(),
      rate: json['rate'] ?? 0,
      ratings: (json['ratings'] as List? ?? [])
          .map((e) => RatingModel.fromJson(e))
          .toList(),
      galleries: (json['galleries'] as List? ?? [])
          .map((e) => GalleryModel.fromJson(e))
          .toList(),
    );
  }
}
