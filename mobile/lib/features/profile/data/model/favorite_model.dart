class FavoriteModel {
  final int id;
  final int favoritableId;
  final String favoritableSlug;
  final String favoritableType;
  final String favoritableImage;
  final String favoritableTitle;
  final String favoritableAddress;
  final double favoritableLat;
  final double favoritableLong;
  final double favoritableRatings;
  final bool favoritableFeatured;
  final Region? favoritableRegion;
  final City? favoritableCity;

  FavoriteModel({
    required this.id,
    required this.favoritableId,
    required this.favoritableSlug,
    required this.favoritableType,
    required this.favoritableImage,
    required this.favoritableTitle,
    required this.favoritableAddress,
    required this.favoritableLat,
    required this.favoritableLong,
    required this.favoritableRatings,
    required this.favoritableFeatured,
    this.favoritableRegion,
    this.favoritableCity,
  });
  factory FavoriteModel.fromJson(Map<String, dynamic> json) {
    return FavoriteModel(
      id: json['id'] ?? 0,
      favoritableId: json['favoritable_id'] ?? 0,
      favoritableSlug: json['favoritable_slug'] ?? '',
      favoritableType: json['favoritable_type'] ?? '',
      favoritableImage: json['favoritable_image'] ?? '',
      favoritableTitle: json['favoritable_title'] ?? '',
      favoritableAddress: json['favoritable_address'] ?? '',
      favoritableLat: (json['favoritable_lat'] ?? 0).toDouble(),
      favoritableLong: (json['favoritable_long'] ?? 0).toDouble(),
      favoritableRatings: (json['favoritable_ratings'] ?? 0).toDouble(),
      favoritableFeatured:
          json['favoritable_featured'] == 1 ||
          json['favoritable_featured'] == true,
      favoritableRegion: json['favoritable_region'] != null
          ? Region.fromJson(json['favoritable_region'])
          : null,
      favoritableCity: json['favoritable_city'] != null
          ? City.fromJson(json['favoritable_city'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'favoritable_id': favoritableId,
      'favoritable_slug': favoritableSlug,
      'favoritable_type': favoritableType,
      'favoritable_image': favoritableImage,
      'favoritable_title': favoritableTitle,
      'favoritable_address': favoritableAddress,
      'favoritable_lat': favoritableLat,
      'favoritable_long': favoritableLong,
      'favoritable_ratings': favoritableRatings,
      'favoritable_featured': favoritableFeatured,
      'favoritable_region': favoritableRegion?.toJson(),
      'favoritable_city': favoritableCity?.toJson(),
    };
  }
}

class Region {
  final int id;
  final String name;

  Region({required this.id, required this.name});

  factory Region.fromJson(Map<String, dynamic> json) {
    return Region(id: json['id'] ?? 0, name: json['name'] ?? '');
  }

  Map<String, dynamic> toJson() {
    return {'id': id, 'name': name};
  }
}

class City {
  final int id;
  final String name;

  City({required this.id, required this.name});

  factory City.fromJson(Map<String, dynamic> json) {
    return City(id: json['id'] ?? 0, name: json['name'] ?? '');
  }

  Map<String, dynamic> toJson() {
    return {'id': id, 'name': name};
  }
}
