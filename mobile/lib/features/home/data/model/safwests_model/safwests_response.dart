import 'package:hawdaj/features/home/data/model/safwests_model/safwest_model.dart';

class SafwestResponse {
  final int currentPage;
  final List<SafwestModel> items;
  final int total;

  SafwestResponse({
    required this.currentPage,
    required this.items,
    required this.total,
  });

  factory SafwestResponse.fromJson(Map<String, dynamic> json) {
    return SafwestResponse(
      currentPage: (json['current_page'] as num?)?.toInt() ?? 0,
      items: (json['items'] as List<dynamic>? ?? [])
          .map((e) => SafwestModel.fromJson(e as Map<String, dynamic>))
          .toList(),
      total: (json['total'] as num?)?.toInt() ?? 0,
    );
  }
}
