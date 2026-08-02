// global_map_data_model.dart
class GlobalMapDataModel {
  final String countryCode;
  final int count;
  final String name;

  GlobalMapDataModel({
    required this.countryCode,
    required this.count,
    required this.name,
  });

  factory GlobalMapDataModel.fromJson(Map<String, dynamic> json) {
    return GlobalMapDataModel(
      countryCode: json['country_code'],
      count: json['count'],
      name: json['name'],
    );
  }
}
