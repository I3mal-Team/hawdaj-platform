import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/home/data/model/explore_category_model.dart';

class LandmarkModel extends Equatable {
  final String id;
  final String name;
  final String description;
  final String address;
  final ExploreCategoryModel category;
  final List<String> images;
  final DateTime createdAt;
  final DateTime updatedAt;

  const LandmarkModel({
    required this.id,
    required this.name,
    required this.description,
    required this.address,
    required this.category,
    required this.images,
    required this.createdAt,
    required this.updatedAt,
  });

  factory LandmarkModel.fromJson(Map<String, dynamic> json) {
    return LandmarkModel(
      id: json['id'] ?? '',
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      address: json['address'] ?? '',
      category: ExploreCategoryModel(
        id: json['category']['id'] ?? '',
        name: json['category']['name'] ?? '',
        imagePath: json['category']['image_path'] ?? '',
        routes: json['category']['routes'] ?? '',
        type: json['category']['type'] ?? '',
        manor: json['category']['manor'] ?? false,
      ),
      images: List<String>.from(json['images'] ?? []),
      createdAt: DateTime.parse(
        json['created_at'] ?? DateTime.now().toIso8601String(),
      ),
      updatedAt: DateTime.parse(
        json['updated_at'] ?? DateTime.now().toIso8601String(),
      ),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'address': address,
      'category': {
        'id': category.id,
        'name': category.name,
        'image_path': category.imagePath,
        'routes': category.routes,
        'type': category.type,
        'manor': category.manor,
      },
      'images': images,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
    };
  }

  @override
  List<Object?> get props => [
    id,
    name,
    description,
    address,
    category,
    images,
    createdAt,
    updatedAt,
  ];
}

class CreateLandmarkRequest {
  final String name;
  final String description;
  final String address;
  final List<String> images;
  final String type;

  const CreateLandmarkRequest({
    required this.name,
    required this.description,
    required this.address,
    required this.images,
    required this.type,
  });

  Map<String, dynamic> toJson() {
    return {
      'title': name,
      'description': description,
      'address': address,
      'type': type,
      'images': images,
    };
  }
}

class CreateLandmarkResponse {
  final int code;
  final String message;

  const CreateLandmarkResponse({required this.code, required this.message});

  factory CreateLandmarkResponse.fromJson(Map<String, dynamic> json) {
    return CreateLandmarkResponse(
      code: json['code'] ?? 0,
      message: json['message'] ?? '',
    );
  }

  bool get success => code == 200;
}
