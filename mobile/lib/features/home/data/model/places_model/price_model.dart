// price_model.dart
class PriceModel {
  final int id;
  final String name;

  PriceModel({required this.id, required this.name});

  factory PriceModel.fromJson(Map<String, dynamic> json) {
    return PriceModel(id: json['id'], name: json['name']);
  }
}
