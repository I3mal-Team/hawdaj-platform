// place_response.dart
import 'package:hawdaj/features/home/data/model/places_model/place_model.dart';

class PlaceResponse {
  final int currentPage;
  final List<PlaceModel> items;
  final int total;

  PlaceResponse({
    required this.currentPage,
    required this.items,
    required this.total,
  });

  factory PlaceResponse.fromJson(Map<String, dynamic> json) {
    return PlaceResponse(
      currentPage: json['current_page'] ?? 1,
      items: (json['items'] as List? ?? [])
          .map((e) => PlaceModel.fromJson(e))
          .toList(),
      total: json['total'] ?? 0,
    );
  }
}
