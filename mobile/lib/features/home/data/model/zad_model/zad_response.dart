import 'package:hawdaj/features/home/data/model/zad_model/zad_model.dart';

class ZadResponse {
  final int currentPage;
  final List<ZadModel> items;
  final int total;

  ZadResponse({
    required this.currentPage,
    required this.items,
    required this.total,
  });

  factory ZadResponse.fromJson(Map<String, dynamic> json) {
    return ZadResponse(
      currentPage: json['current_page'],
      items: (json['items'] as List).map((e) => ZadModel.fromJson(e)).toList(),
      total: json['total'],
    );
  }
}
