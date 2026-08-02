import 'city.dart';
import 'region.dart';

class ItemGlodal {
  int? id;
  String? slug;
  String? type;
  String? image;
  dynamic address;
  double? lat;
  double? long;
  int? featured;
  String? title;
  int? ratingsCount;
  int? rate;
  City? city;
  Region? region;
  bool? isFavorite;

  ItemGlodal({
    this.id,
    this.slug,
    this.type,
    this.image,
    this.address,
    this.lat,
    this.long,
    this.featured,
    this.title,
    this.ratingsCount,
    this.rate,
    this.city,
    this.region,
    this.isFavorite,
  });

  factory ItemGlodal.fromJson(Map<String, dynamic> json) => ItemGlodal(
        id: json['id'] as int?,
        slug: json['slug'] as String?,
        type: json['type'] as String?,
        image: json['image'] as String?,
        address: json['address'] as dynamic,
        lat: (json['lat'] as num?)?.toDouble(),
        long: (json['long'] as num?)?.toDouble(),
        featured: json['featured'] is bool
            ? (json['featured'] ? 1 : 0) // Handle boolean to int conversion
            : json['featured'] as int?,
        title: json['title'] as String?,
        ratingsCount: json['ratings_count'] as int?,
        rate: json['rate'] as int?,
        city: json['city'] == null
            ? null
            : City.fromJson(json['city'] as Map<String, dynamic>),
        region: json['region'] == null
            ? null
            : Region.fromJson(json['region'] as Map<String, dynamic>),
        isFavorite: json['is_favorite'] as bool?,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'slug': slug,
        'type': type,
        'image': image,
        'address': address,
        'lat': lat,
        'long': long,
        'featured': featured,
        'title': title,
        'ratings_count': ratingsCount,
        'rate': rate,
        'city': city?.toJson(),
        'region': region?.toJson(),
        'is_favorite': isFavorite,
      };
}
