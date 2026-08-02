class CityModel {
  final int id;
  final String name;

  CityModel({required this.id, required this.name});

  factory CityModel.fromJson(Map<String, dynamic> json) {
    try {
      return CityModel(id: json['id'], name: json['name']);
    } catch (e) {
      return CityModel(id: 0, name: '');
    }
  }

  Map<String, dynamic> toJson() {
    return {'id': id, 'name': name};
  }
}
