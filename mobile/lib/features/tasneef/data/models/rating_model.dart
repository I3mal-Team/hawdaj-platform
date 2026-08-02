class RatingModel {
  final int id;
  final String name;
  final String email;
  final String rateText;
  final String rate;
  final String type;
  final int parentId;
  final String createdAt;
  final String updatedAt;
  final int? userId;

  RatingModel({
    required this.id,
    required this.name,
    required this.email,
    required this.rateText,
    required this.rate,
    required this.type,
    required this.parentId,
    required this.createdAt,
    required this.updatedAt,
    this.userId,
  });

  factory RatingModel.fromJson(Map<String, dynamic> json) {
    return RatingModel(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      rateText: json['rateText'],
      rate: json['rate'],
      type: json['type'],
      parentId: json['parent_id'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
      userId: json['user_id'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'rateText': rateText,
      'rate': rate,
      'type': type,
      'parent_id': parentId,
      'created_at': createdAt,
      'updated_at': updatedAt,
      'user_id': userId,
    };
  }
}
