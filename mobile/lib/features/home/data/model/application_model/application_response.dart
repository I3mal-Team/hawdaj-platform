// application_response.dart
import 'package:hawdaj/features/home/data/model/application_model/application_model.dart';

class ApplicationResponse {
  final int currentPage;
  final List<ApplicationModel> items;
  final int total;

  ApplicationResponse({
    required this.currentPage,
    required this.items,
    required this.total,
  });

  factory ApplicationResponse.fromJson(Map<String, dynamic> json) {
    return ApplicationResponse(
      currentPage: json['current_page'],
      items: (json['items'] as List)
          .map((e) => ApplicationModel.fromJson(e))
          .toList(),
      total: json['total'],
    );
  }
}
