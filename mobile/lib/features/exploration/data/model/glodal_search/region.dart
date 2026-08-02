class Region {
  int? id;
  String? name;

  Region({this.id, this.name});

  factory Region.fromJson(Map<String, dynamic> json) => Region(
        id: json['id'] as int?,
        name: json['name'] as String?,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
      };
}
