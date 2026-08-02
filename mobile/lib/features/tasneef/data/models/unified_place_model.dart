import 'package:easy_localization/easy_localization.dart';

import 'category_model.dart';
import 'gallery_model.dart';
import 'price_model.dart';
import 'city_model.dart';
import 'region_model.dart';
import 'rating_model.dart';
import 'tasneef_item.dart';
import '../../../../core/databases/api/end_points.dart';

class UnifiedPlaceModel implements TasneefItem {
  @override
  final int id;
  final String slug;
  final String type;

  final List<CategoryModel> categories;
  final List<dynamic> foodCategories; // For zads

  final String? address;
  final String image;
  final String? coverImage;
  final String? menuFile; // For zads

  final String? ticketLink;
  final String? websiteLink;
  final String? instagramLink;
  final String? whatsapp;
  final String? facebookLink;

  // Event-specific fields
  final String? dateFrom;
  final String? dateTo;
  final dynamic closed; // bool|int
  final String? website;
  final String? displayType;
  final String? videoUrl;
  final String? link;

  final String? temperature;

  final List<String> seasons;
  final List<dynamic> relatedPlaces;
  final List<dynamic> nearPlaces;
  final dynamic related; // For events

  final List<GalleryModel> galleries;
  final PriceModel? price;
  final CityModel? city;
  final RegionModel? region;

  final String addressType;
  final dynamic active; // bool|int
  final int viewsNum;
  final String? nearStores;

  final dynamic _featured; // bool|int
  final double lat;
  final double long;
  final dynamic _visited; // bool|int

  final String? distance;
  final String? keyWords;
  final int? prefered;
  final String? placeIcon;
  final String? createdAt;

  @override
  final num rate;
  final int review;

  @override
  final String title;
  @override
  final String description;

  final List<RatingModel> ratings;
  final Map<String, dynamic>? meta;

  final dynamic _isFavorite; // bool|int
  final dynamic _isSaved; // bool|int

  // Store-specific fields
  final bool? isOnline;

  // Story (swalef) specific fields
  final String? audioStoryLink;
  final String? audioStoryTitle;
  final List<String> mainCharacters;
  final String? content;
  final String? timePeriod;
  final String? storyImportance;
  final String? relevanceToPresent;
  final String? source;
  final bool? showInHome;
  final List<dynamic> translations;

  // Application (app) specific fields
  final String? iosLink;
  final String? androidLink;

  // Guide-specific fields
  final String? nickName;
  final int? experience;
  final String? gender;
  final List<dynamic> regions;
  final List<dynamic> languages;
  final Map<String, dynamic>? social;

  UnifiedPlaceModel({
    required this.id,
    required this.slug,
    required this.type,
    required this.categories,
    this.foodCategories = const [],
    this.address,
    required this.image,
    this.coverImage,
    this.menuFile,
    this.ticketLink,
    this.websiteLink,
    this.instagramLink,
    this.whatsapp,
    this.facebookLink,
    this.dateFrom,
    this.dateTo,
    this.closed,
    this.website,
    this.displayType,
    this.videoUrl,
    this.link,
    this.temperature,
    this.seasons = const [],
    this.relatedPlaces = const [],
    this.nearPlaces = const [],
    this.related,
    required this.galleries,
    this.price,
    this.city,
    this.region,
    required this.addressType,
    required this.active,
    required this.viewsNum,
    this.nearStores,
    required featured,
    required this.lat,
    required this.long,
    required visited,
    this.distance,
    this.keyWords,
    this.prefered,
    this.createdAt,
    this.placeIcon,
    required this.rate,
    required this.review,
    required this.title,
    required this.description,
    required this.ratings,
    this.meta,
    required isFavorite,
    required isSaved,
    this.isOnline,
    // Story
    this.audioStoryLink,
    this.audioStoryTitle,
    this.mainCharacters = const [],
    this.content,
    this.timePeriod,
    this.storyImportance,
    this.relevanceToPresent,
    this.source,
    this.showInHome,
    this.translations = const [],
    // App
    this.iosLink,
    this.androidLink,
    // Guide
    this.nickName,
    this.experience,
    this.gender,
    this.regions = const [],
    this.languages = const [],
    this.social,
  }) : _featured = featured,
       _visited = visited,
       _isFavorite = isFavorite,
       _isSaved = isSaved;

  // ================= Normalized flags =================
  bool get isActiveStatus => active == 1 || active == true;
  bool get isFeaturedStatus => _featured == 1 || _featured == true;
  bool get isVisitedStatus => _visited == 1 || _visited == true;
  bool get isClosedStatus => closed == 1 || closed == true;
  bool get isFavoriteStatus => _isFavorite == 1 || _isFavorite == true;
  bool get isSavedStatus => _isSaved == 1 || _isSaved == true;

  @override
  int get featured =>
      (_featured is int) ? _featured : (isFeaturedStatus ? 1 : 0);
  @override
  bool get isFavorite => isFavoriteStatus;

  bool get visited => isVisitedStatus;
  bool get isSaved => isSavedStatus;
  bool get isActive => isActiveStatus;
  bool get isClosed => isClosedStatus;

  // ================= URL helpers =================
  @override
  String get fullImageUrl {
    if (image.startsWith('http')) return image;
    return '${EndPoints.imageUrl}$image';
  }

  String get fullCoverImageUrl {
    final c = coverImage;
    if (c == null || c.isEmpty) return fullImageUrl;
    if (c.startsWith('http')) return c;
    return '${EndPoints.imageUrl}$c';
  }

  String? get fullPlaceIconUrl {
    final p = placeIcon;
    if (p == null || p.isEmpty) return null;
    if (p.startsWith('http')) return p;
    return '${EndPoints.imageUrl}$p';
  }

  // ================= Type helpers =================
  bool get isStore => type == 'store';
  bool get isPlace => type == 'place';
  bool get isEvent => type == 'event';
  bool get isStory => type == 'swalef';
  bool get isApp => type == 'app';
  bool get isGuide => type == 'guide';

  // ================= UI helpers =================
  String get mainCharactersText =>
      mainCharacters.isEmpty ? '' : mainCharacters.join('، ');

  String get languagesText {
    if (languages.isEmpty) return '';
    final names = <String>[];
    for (final l in languages) {
      if (l is Map<String, dynamic> && l['name'] != null) {
        names.add(l['name'].toString());
      }
    }
    return names.join('، ');
  }

  String get regionsText {
    if (regions.isEmpty) return '';
    final names = <String>[];
    for (final r in regions) {
      if (r is Map<String, dynamic> && r['name'] != null) {
        names.add(r['name'].toString());
      }
    }
    return names.join('، ');
  }

  // For TasneefItem (location text)
  @override
  String get locationText {
    if (isGuide && regions.isNotEmpty) {
      final names = <String>[];
      for (final r in regions) {
        if (r is Map<String, dynamic> && r['name'] != null) {
          names.add(r['name'].toString());
        }
      }
      return names.isNotEmpty ? names.join('، ') : "not_specified".tr();
    }

    if (isStory && timePeriod != null && timePeriod!.isNotEmpty) {
      return timePeriod!;
    }

    if (isApp) {
      final stores = <String>[];
      if (iosLink != null) stores.add('App Store');
      if (androidLink != null) stores.add('Google Play');
      return stores.isNotEmpty ? stores.join(' • ') : 'غير متوفر';
    }

    if (city != null && region != null) {
      return '  ${region!.name} , ${city!.name}';
    } else if (city != null) {
      return city!.name;
    } else if (region != null) {
      return region!.name;
    }

    if (type == 'store') {
      return isOnline == true ? "store_online".tr() : "store_physical".tr();
    }
    if (address != null && address!.isNotEmpty) {
      return address!;
    }

    return "not_specified".tr();
  }

  // For TasneefItem (price text)
  @override
  String get priceText {
    if (isGuide && experience != null) {
      // تجربة: دعم صيغة مفرد/جمع بسيطة
      final years = experience is int
          ? experience as int
          : int.tryParse('$experience') ?? 0;
      final yearWord = years == 1 ? 'year_singular'.tr() : 'year_plural'.tr();
      return '$years $yearWord ${'experience'.tr()}';
    }

    if (isStory) return "story_heritage".tr();
    if (isApp) {
      if (categories.isNotEmpty) {
        return categories.first.name ?? "not_specified".tr();
      }
      if (categories.isNotEmpty) return categories.first.name ?? '';
      return "app_free".tr();
    }
    if (price != null) return price!.name;
    if (type == 'store')
      return isOnline == true ? "store_online".tr() : "store_physical".tr();
    return "not_specified".tr();
  }

  // ================= Factory =================
  /// Factory مرن يقبل `int` (id فقط) أو `Map`
  factory UnifiedPlaceModel.fromJson(dynamic source) {
    if (source is int) {
      // ID فقط — نبني نسخة خفيفة
      return UnifiedPlaceModel(
        id: source,
        slug: '',
        type: '',
        categories: const [],
        image: '',
        galleries: const [],
        addressType: '',
        active: 0,
        viewsNum: 0,
        featured: 0,
        lat: 0.0,
        long: 0.0,
        visited: 0,
        rate: 0,
        review: 0,
        title: '',
        description: '',
        ratings: const [],
        isFavorite: 0,
        isSaved: 0,
      );
    }

    if (source is! Map<String, dynamic>) {
      throw FormatException(
        'UnifiedPlaceModel.fromJson expects Map or int, got ${source.runtimeType}',
        source,
      );
    }
    final json = source;

    final categories = _toList(json['categories'])
        .whereType<Map<String, dynamic>>()
        .map((e) => CategoryModel.fromJson(e))
        .toList();

    final galleries = _toList(
      json['galleries'],
    ).map((e) => GalleryModel.fromJson(e)).toList();

    final seasons = _toList(
      json['seasons'],
    ).map((e) => e?.toString() ?? '').where((e) => e.isNotEmpty).toList();

    final ratings = _toList(
      json['ratings'],
    ).map((e) => RatingModel.fromJson(e)).toList();

    return UnifiedPlaceModel(
      id: _asInt(json['id']),
      slug: _asString(json['slug']),
      type: _asString(json['type']),
      categories: categories,
      foodCategories: _toList(json['food_categories']),
      address: _asNullableString(json['address']),
      image: _asString(json['image']),
      coverImage: _asNullableString(json['cover_image']),
      menuFile: _asNullableString(json['menu_file']),
      ticketLink: _asNullableString(json['ticket_link']),
      createdAt: _asNullableString(json['created_at']),
      websiteLink: _asNullableString(json['website_link']),
      instagramLink: _asNullableString(
        json['instagram_link'] ?? json['Instagram_link'],
      ),
      whatsapp: _asNullableString(json['whatsapp']),
      facebookLink: _asNullableString(json['facebook_link']),
      dateFrom: _asNullableString(json['date_from']),
      dateTo: _asNullableString(json['date_to']),
      closed: json['closed'] ?? 0,
      website: _asNullableString(json['website']),
      displayType: _asNullableString(json['display_type']),
      videoUrl: _asNullableString(json['video_url']),
      link: _asNullableString(json['link']),
      temperature: _asNullableString(json['temperature']),
      seasons: seasons,
      relatedPlaces: json['related_places'] ?? const [],
      nearPlaces: json['near_places'] ?? const [],
      related: json['related'],
      galleries: galleries,
      price: json['price'] != null ? PriceModel.fromJson(json['price']) : null,
      city: json['city'] != null ? CityModel.fromJson(json['city']) : null,
      region: json['region'] != null
          ? RegionModel.fromJson(json['region'])
          : null,
      addressType: _asString(json['address_type']),
      active: json['active'] ?? 0,
      viewsNum: _asInt(json['views_num']),
      nearStores: _asNullableString(json['near_stores']),
      featured: json['featured'] ?? 0,
      lat: _asDouble(json['lat']),
      long: _asDouble(json['long']),
      visited: json['visited'] ?? 0,
      distance: _asNullableString(json['distance']),
      keyWords: _asNullableString(json['key_words']),
      prefered: _asIntOrNull(json['prefered']),
      placeIcon: _asNullableString(json['place_icon']),
      rate: _asNum(json['rate'], fallback: 0),
      review: _asInt(json['review']),
      title: _asString(json['title']),
      description: _asString(json['description']),
      ratings: ratings,
      meta: (json['meta'] is Map<String, dynamic>)
          ? json['meta'] as Map<String, dynamic>
          : null,
      isFavorite: json['is_favorite'] ?? 0,
      isSaved: json['is_saved'] ?? 0,
      isOnline: _asBoolOrNull(json['is_online']),
      // story
      audioStoryLink: _asNullableString(json['audioStoryLink']),
      audioStoryTitle: _asNullableString(json['audioStoryTitle']),
      mainCharacters: _parseMainCharacters(json['mainCharacters']),
      content: _asNullableString(json['content']),
      timePeriod: _asNullableString(json['timePeriod']),
      storyImportance: _asNullableString(json['storyImportance']),
      relevanceToPresent: _asNullableString(json['relevanceToPresent']),
      source: _asNullableString(json['source']),
      showInHome: _asBoolOrNull(json['show_in_home']),
      translations: json['translations'] is List
          ? json['translations'] as List
          : const [],
      // app
      iosLink: _asNullableString(json['ios_link']),
      androidLink: _asNullableString(json['android_link']),
      // guide
      nickName: _asNullableString(json['nickName']),
      experience: _asIntOrNull(json['experience']),
      gender: _asNullableString(json['gender']),
      regions: json['regions'] is List ? json['regions'] as List : const [],
      languages: json['languages'] is List
          ? json['languages'] as List
          : const [],
      social: (json['social'] is Map<String, dynamic>)
          ? json['social'] as Map<String, dynamic>
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'slug': slug,
      'type': type,
      'categories': categories.map((c) => c.toJson()).toList(),
      'food_categories': foodCategories,
      'address': address,
      'image': image,
      'cover_image': coverImage,
      'menu_file': menuFile,
      'ticket_link': ticketLink,
      'website_link': websiteLink,
      'instagram_link': instagramLink,
      'whatsapp': whatsapp,
      'facebook_link': facebookLink,
      'date_from': dateFrom,
      'date_to': dateTo,
      'closed': closed,
      'website': website,
      'display_type': displayType,
      'video_url': videoUrl,
      'link': link,
      'temperature': temperature,
      'seasons': seasons,
      'related_places': relatedPlaces,
      'near_places': nearPlaces,
      'related': related,
      'galleries': galleries.map((g) => g.toJson()).toList(),
      'price': price?.toJson(),
      'city': city?.toJson(),
      'region': region?.toJson(),
      'address_type': addressType,
      'active': active,
      'views_num': viewsNum,
      'near_stores': nearStores,
      'featured': _featured,
      'lat': lat,
      'long': long,
      'visited': _visited,
      'distance': distance,
      'key_words': keyWords,
      'prefered': prefered,
      'place_icon': placeIcon,
      'rate': rate,
      'review': review,
      'title': title,
      'description': description,
      'ratings': ratings.map((r) => r.toJson()).toList(),
      'meta': meta,
      'is_favorite': _isFavorite,
      'is_saved': _isSaved,
      'is_online': isOnline,
      // story
      'audioStoryLink': audioStoryLink,
      'audioStoryTitle': audioStoryTitle,
      'mainCharacters': mainCharacters,
      'content': content,
      'timePeriod': timePeriod,
      'storyImportance': storyImportance,
      'relevanceToPresent': relevanceToPresent,
      'source': source,
      'show_in_home': showInHome,
      'translations': translations,
      // app
      'ios_link': iosLink,
      'android_link': androidLink,
      // guide
      'nickName': nickName,
      'experience': experience,
      'gender': gender,
      'regions': regions,
      'languages': languages,
      'social': social,
    };
  }

  // ================= Private helpers =================
  static List<dynamic> _toList(dynamic v) {
    if (v == null) return const [];
    if (v is List) return v;
    return [v];
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

  static int _asInt(dynamic v, {int fallback = 0}) {
    if (v == null) return fallback;
    if (v is int) return v;
    if (v is num) return v.toInt();
    if (v is String) return int.tryParse(v) ?? fallback;
    return fallback;
  }

  static int? _asIntOrNull(dynamic v) {
    if (v == null) return null;
    if (v is int) return v;
    if (v is num) return v.toInt();
    if (v is String) return int.tryParse(v);
    return null;
  }

  static double _asDouble(dynamic v, {double fallback = 0.0}) {
    if (v == null) return fallback;
    if (v is double) return v;
    if (v is int) return v.toDouble();
    if (v is num) return v.toDouble();
    if (v is String) return double.tryParse(v) ?? fallback;
    return fallback;
  }

  static num _asNum(dynamic v, {num fallback = 0}) {
    if (v == null) return fallback;
    if (v is num) return v;
    if (v is String) return num.tryParse(v) ?? fallback;
    return fallback;
  }

  static bool? _asBoolOrNull(dynamic v) {
    if (v == null) return null;
    if (v is bool) return v;
    if (v is int) return v != 0;
    if (v is String) {
      final s = v.toLowerCase().trim();
      if (s == 'true' || s == '1' || s == 'yes') return true;
      if (s == 'false' || s == '0' || s == 'no') return false;
    }
    return null;
  }

  // Parse story main characters
  static List<String> _parseMainCharacters(dynamic mainCharactersJson) {
    if (mainCharactersJson == null) return [];
    try {
      if (mainCharactersJson is List) {
        final result = <String>[];
        for (var item in mainCharactersJson) {
          if (item is String) {
            final chars = item
                .split('•')
                .map((c) => c.trim().replaceAll(RegExp(r'[\n\t]'), ''))
                .where((c) => c.isNotEmpty)
                .toList();
            result.addAll(chars);
          } else {
            result.add(item.toString());
          }
        }
        return result;
      }
      if (mainCharactersJson is String) {
        return mainCharactersJson
            .split('•')
            .map((c) => c.trim().replaceAll(RegExp(r'[\n\t]'), ''))
            .where((c) => c.isNotEmpty)
            .toList();
      }
      return [];
    } catch (_) {
      return [];
    }
  }
}
