import 'package:equatable/equatable.dart';

class PrepareTripModel extends Equatable {
  final List<int> categories;
  final String daterange;
  final String funnyPlacePerDay;
  final String lat1;
  final String lat2;
  final String long1;
  final String long2;
  final List<int> price;
  final String region1;
  final String region2;
  final String type;
  final String vehicleType;

  const PrepareTripModel({
    required this.categories,
    required this.daterange,
    required this.funnyPlacePerDay,
    required this.lat1,
    required this.lat2,
    required this.long1,
    required this.long2,
    required this.price,
    required this.region1,
    required this.region2,
    required this.type,
    required this.vehicleType,
  });

  PrepareTripModel copyWith({
    List<int>? categories,
    String? daterange,
    String? funnyPlacePerDay,
    String? lat1,
    String? lat2,
    String? long1,
    String? long2,
    List<int>? price,
    String? region1,
    String? region2,
    String? type,
    String? vehicleType,
  }) {
    return PrepareTripModel(
      categories: categories ?? this.categories,
      daterange: daterange ?? this.daterange,
      funnyPlacePerDay: funnyPlacePerDay ?? this.funnyPlacePerDay,
      lat1: lat1 ?? this.lat1,
      lat2: lat2 ?? this.lat2,
      long1: long1 ?? this.long1,
      long2: long2 ?? this.long2,
      price: price ?? this.price,
      region1: region1 ?? this.region1,
      region2: region2 ?? this.region2,
      type: type ?? this.type,
      vehicleType: vehicleType ?? this.vehicleType,
    );
  }

  factory PrepareTripModel.fromJson(Map<String, dynamic> json) {
    return PrepareTripModel(
      categories: (json['categories'] as List<dynamic>? ?? [])
          .map((e) => (e as num).toInt())
          .toList(),
      daterange: (json['daterange'] ?? '').toString(),
      funnyPlacePerDay: (json['funny_place_per_day'] ?? '').toString(),
      lat1: (json['lat1'] ?? '').toString(),
      lat2: (json['lat2'] ?? '').toString(),
      long1: (json['long1'] ?? '').toString(),
      long2: (json['long2'] ?? '').toString(),
      price: (json['price'] as List<dynamic>? ?? [])
          .map((e) => (e as num).toInt())
          .toList(),
      region1: (json['region1'] ?? '').toString(),
      region2: (json['region2'] ?? '').toString(),
      type: (json['type'] ?? '').toString(),
      vehicleType: (json['vehicleType'] ?? '').toString(),
    );
  }

  Map<String, dynamic> toJson() => {
    'categories': categories,
    'daterange': daterange,
    'funny_place_per_day': funnyPlacePerDay,
    'lat1': lat1,
    'lat2': lat2,
    'long1': long1,
    'long2': long2,
    'price': price,
    'region1': region1,
    'region2': region2,
    'type': type,
    'vehicleType': vehicleType,
  };

  @override
  List<Object?> get props => [
    categories,
    daterange,
    funnyPlacePerDay,
    lat1,
    lat2,
    long1,
    long2,
    price,
    region1,
    region2,
    type,
    vehicleType,
  ];
}
