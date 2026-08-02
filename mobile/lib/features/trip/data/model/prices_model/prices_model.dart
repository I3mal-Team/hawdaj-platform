class PricesModel {
  final int? id;
  final String? name;
  final String? icon;

  PricesModel({this.id, this.name, this.icon});

  factory PricesModel.fromJson(Map<String, dynamic> json) => PricesModel(
    id: json['id'] as int?,
    name: json['name'] as String?,
    icon: json['icon'] as String?,
  );

  Map<String, dynamic> toJson() => {'id': id, 'name': name, 'icon': icon};
}
