// guide_response.dart
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';

class GuideResponse {
  final int currentPage;
  final List<GuideModel> items;
  final int total;

  GuideResponse({
    required this.currentPage,
    required this.items,
    required this.total,
  });

  factory GuideResponse.fromJson(Map<String, dynamic> json) {
    return GuideResponse(
      currentPage: json['current_page'],
      items: (json['items'] as List)
          .map((e) => GuideModel.fromJson(e))
          .toList(),
      total: json['total'],
    );
  }
}
