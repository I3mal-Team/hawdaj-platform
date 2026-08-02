import 'dart:convert';

/// ===============================
/// 🧩 Root Model
/// ===============================
class MyPropertiesResponse {
  final int code;
  final String message;
  final MyPropertiesData data;

  MyPropertiesResponse({
    required this.code,
    required this.message,
    required this.data,
  });

  factory MyPropertiesResponse.fromJson(Map<String, dynamic> json) {
    return MyPropertiesResponse(
      code: json['code'] ?? 0,
      message: json['message'] ?? '',
      data: MyPropertiesData.fromJson(json['data'] ?? {}),
    );
  }

  Map<String, dynamic> toJson() => {
    'code': code,
    'message': message,
    'data': data.toJson(),
  };
}

/// ===============================
/// 🗂️ Data (contains lists of each type)
/// ===============================
class MyPropertiesData {
  final PropertyList places;
  final PropertyList stores;
  final PropertyList events;
  final PropertyList zadElgadels;

  MyPropertiesData({
    required this.places,
    required this.stores,
    required this.events,
    required this.zadElgadels,
  });

  factory MyPropertiesData.fromJson(Map<String, dynamic> json) {
    return MyPropertiesData(
      places: PropertyList.fromJson(json['places'] ?? {}),
      stores: PropertyList.fromJson(json['stores'] ?? {}),
      events: PropertyList.fromJson(json['events'] ?? {}),
      zadElgadels: PropertyList.fromJson(json['zad_elgadels'] ?? {}),
    );
  }

  Map<String, dynamic> toJson() => {
    'places': places.toJson(),
    'stores': stores.toJson(),
    'events': events.toJson(),
    'zad_elgadels': zadElgadels.toJson(),
  };
}

/// ===============================
/// 📋 Generic Paginated List
/// ===============================
class PropertyList {
  final int? currentPage;
  final List<PropertyItem> items;
  final int? total;

  PropertyList({
    required this.currentPage,
    required this.items,
    required this.total,
  });

  factory PropertyList.fromJson(Map<String, dynamic> json) {
    return PropertyList(
      currentPage: json['current_page'],
      total: json['total'],
      items:
          (json['items'] as List?)
              ?.map((e) => PropertyItem.fromJson(e))
              .toList() ??
          [],
    );
  }

  Map<String, dynamic> toJson() => {
    'current_page': currentPage,
    'items': items.map((e) => e.toJson()).toList(),
    'total': total,
  };
}

/// ===============================
/// 🏠 Property Item
/// ===============================
class PropertyItem {
  final int id;
  final String slug;
  final String type;
  final String? title;
  final String? description;
  final String? address;
  final String? image;
  final String? coverImage;
  final String? ownershipProofFile;
  final String? status;
  final int? active;
  final int? viewsNum;
  final bool? featured;
  final double? lat;
  final double? long;
  final String? whatsapp;
  final String? facebookLink;
  final String? instagramLink;
  final String? websiteLink;

  final List<Category> categories;
  final List<String>? seasons;
  final Price? price;
  final City? city;
  final Region? region;

  // ✅ Additional fields
  final bool? isFavorite;
  final bool? isSaved;
  final int? rate;
  final int? review;
  final String? rejectedReason;
  final String? addressType;

  PropertyItem({
    required this.id,
    required this.slug,
    required this.type,
    this.title,
    this.description,
    this.address,
    this.image,
    this.coverImage,
    this.ownershipProofFile,
    this.status,
    this.active,
    this.viewsNum,
    this.featured,
    this.lat,
    this.long,
    this.whatsapp,
    this.facebookLink,
    this.instagramLink,
    this.websiteLink,
    required this.categories,
    this.seasons,
    this.price,
    this.city,
    this.region,
    this.isFavorite,
    this.isSaved,
    this.rate,
    this.review,
    this.rejectedReason,
    this.addressType,
  });

  factory PropertyItem.fromJson(Map<String, dynamic> json) {
    bool _toBool(dynamic value) {
      if (value == null) return false;
      if (value is bool) return value;
      if (value is int) return value == 1;
      if (value is String) {
        final v = value.toLowerCase();
        return v == '1' || v == 'true';
      }
      return false;
    }

    return PropertyItem(
      id: json['id'] ?? 0,
      slug: json['slug'] ?? '',
      type: json['type'] ?? '',
      title: json['title'],
      description: json['description'],
      address: json['address'],
      image: json['image'],
      coverImage: json['cover_image'],
      ownershipProofFile: json['ownership_proof_file'],
      status: json['status'],

      /// ✅ Handle int/bool safely
      active: (json['active'] is int)
          ? json['active']
          : (_toBool(json['active']) ? 1 : 0),
      viewsNum: json['views_num'] ?? 0,
      featured: _toBool(json['featured']),
      lat: (json['lat'] as num?)?.toDouble(),
      long: (json['long'] as num?)?.toDouble(),
      whatsapp: json['whatsapp'],
      facebookLink: json['facebook_link'],
      instagramLink: json['instagram_link'],
      websiteLink: json['website_link'],

      categories:
          (json['categories'] as List?)
              ?.map((e) => Category.fromJson(e))
              .toList() ??
          [],
      seasons: (json['seasons'] as List?)?.map((e) => e.toString()).toList(),
      price: json['price'] != null ? Price.fromJson(json['price']) : null,
      city: json['city'] != null ? City.fromJson(json['city']) : null,
      region: json['region'] != null ? Region.fromJson(json['region']) : null,

      /// ✅ Handle bool/int conversions
      isFavorite: _toBool(json['is_favorite']),
      isSaved: _toBool(json['is_saved']),
      rate: json['rate'] ?? 0,
      review: json['review'] ?? 0,
      rejectedReason: json['rejected_reason'],
      addressType: json['address_type'],
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'slug': slug,
    'type': type,
    'title': title,
    'description': description,
    'address': address,
    'image': image,
    'cover_image': coverImage,
    'ownership_proof_file': ownershipProofFile,
    'status': status,
    'active': active,
    'views_num': viewsNum,
    'featured': featured,
    'lat': lat,
    'long': long,
    'whatsapp': whatsapp,
    'facebook_link': facebookLink,
    'instagram_link': instagramLink,
    'website_link': websiteLink,
    'categories': categories.map((e) => e.toJson()).toList(),
    'seasons': seasons,
    'price': price?.toJson(),
    'city': city?.toJson(),
    'region': region?.toJson(),
    'is_favorite': isFavorite,
    'is_saved': isSaved,
    'rate': rate,
    'review': review,
    'rejected_reason': rejectedReason,
    'address_type': addressType,
  };
}

/// ===============================
/// 🏷️ Category
/// ===============================
class Category {
  final int id;
  final String name;
  final String? icon;

  Category({required this.id, required this.name, this.icon});

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      icon: json['icon'],
    );
  }

  Map<String, dynamic> toJson() => {'id': id, 'name': name, 'icon': icon};
}

/// ===============================
/// 💰 Price
/// ===============================
class Price {
  final int id;
  final String name;

  Price({required this.id, required this.name});

  factory Price.fromJson(Map<String, dynamic> json) {
    return Price(id: json['id'] ?? 0, name: json['name'] ?? '');
  }

  Map<String, dynamic> toJson() => {'id': id, 'name': name};
}

/// ===============================
/// 🏙️ City
/// ===============================
class City {
  final int id;
  final String name;

  City({required this.id, required this.name});

  factory City.fromJson(Map<String, dynamic> json) {
    return City(id: json['id'] ?? 0, name: json['name'] ?? '');
  }

  Map<String, dynamic> toJson() => {'id': id, 'name': name};
}

/// ===============================
/// 🌍 Region
/// ===============================
class Region {
  final int id;
  final String name;

  Region({required this.id, required this.name});

  factory Region.fromJson(Map<String, dynamic> json) {
    return Region(id: json['id'] ?? 0, name: json['name'] ?? '');
  }

  Map<String, dynamic> toJson() => {'id': id, 'name': name};
}
