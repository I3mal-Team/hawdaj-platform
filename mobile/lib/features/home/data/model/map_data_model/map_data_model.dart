// map_data_model.dart
class MapDataModel {
  final int count;
  final String? latitude;
  final String? longitude;
  final String name;

  MapDataModel({
    required this.count,
    required this.latitude,
    required this.longitude,
    required this.name,
  });

  factory MapDataModel.fromJson(Map<String, dynamic> json) {
    return MapDataModel(
      count: json['count'],
      latitude: json['latitude'],
      longitude: json['longitude'],
      name: json['name'],
    );
  }
}
