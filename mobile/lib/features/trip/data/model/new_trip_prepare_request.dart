import 'package:dio/dio.dart';

class NewTripPrepareParams {
  final String startDate;
  final String endDate;
  final String startRegionId;
  final String endRegionId;
  final String placesPerDay;
  final List<String> categories;
  final List<String> priceRange;
  final String vehicleType;

  NewTripPrepareParams({
    required this.startDate,
    required this.endDate,
    required this.startRegionId,
    required this.endRegionId,
    required this.placesPerDay,
    required this.categories,
    required this.priceRange,
    required this.vehicleType,
  });

  // Convert to FormData for Dio
  FormData toFormData() {
    Map<String, dynamic> map = {
      'start_date': startDate,
      'end_date': endDate,
      'start_region_id': startRegionId,
      'end_region_id': endRegionId,
      'places_per_day': placesPerDay,
      'vehicleType': vehicleType,
    };

    // Add array parameters
    for (int i = 0; i < categories.length; i++) {
      map['categories[]'] = categories;
    }

    for (int i = 0; i < priceRange.length; i++) {
      map['price_range[]'] = priceRange;
    }

    return FormData.fromMap(map);
  }

  // Convert to JSON Map
  Map<String, dynamic> toJson() {
    return {
      'start_date': startDate,
      'end_date': endDate,
      'start_region_id': startRegionId,
      'end_region_id': endRegionId,
      'places_per_day': placesPerDay,
      'categories': categories,
      'price_range': priceRange,
      'vehicleType': vehicleType,
    };
  }

  // Create from JSON
  factory NewTripPrepareParams.fromJson(Map<String, dynamic> json) {
    return NewTripPrepareParams(
      startDate: json['start_date'] ?? '',
      endDate: json['end_date'] ?? '',
      startRegionId: json['start_region_id'] ?? '',
      endRegionId: json['end_region_id'] ?? '',
      placesPerDay: json['places_per_day'] ?? '',
      categories: List<String>.from(json['categories'] ?? []),
      priceRange: List<String>.from(json['price_range'] ?? []),
      vehicleType: json['vehicleType'] ?? '',
    );
  }
}
