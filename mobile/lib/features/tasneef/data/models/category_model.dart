class CategoryModel {
  final int id;
  final String? icon;
  final String? name;
  final String? notes;

  CategoryModel({required this.id, this.icon, this.name, this.notes});

  factory CategoryModel.fromJson(Map<String, dynamic> json) {
    return CategoryModel(
      id: int.tryParse((json['id'] ?? 0).toString()) ?? 0,
      icon: json['icon'] as String?,
      name: json['name'] as String?,
      notes: json['notes'] as String?,
    );
  }

  Map<String, dynamic> toJson() {
    return {'id': id, 'icon': icon, 'name': name, 'notes': notes};
  }
}
