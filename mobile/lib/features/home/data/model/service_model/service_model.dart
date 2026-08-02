// service_model.dart
class ServiceModel {
  final int id;
  final String value;

  ServiceModel({required this.id, required this.value});

  factory ServiceModel.fromJson(Map<String, dynamic> json) {
    return ServiceModel(id: json['id'], value: json['value']);
  }
}
