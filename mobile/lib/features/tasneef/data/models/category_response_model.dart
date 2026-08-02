import 'category_model.dart';

class CategoryResponseModel {
  final int code;
  final String message;
  final List<CategoryModel> data;

  CategoryResponseModel({
    required this.code,
    required this.message,
    required this.data,
  });

  factory CategoryResponseModel.fromJson(Map<String, dynamic> json) {
    return CategoryResponseModel(
      code: json['code'] ?? 0,
      message: json['message'] ?? '',
      data: (json['data'] as List<dynamic>? ?? [])
          .map((e) => CategoryModel.fromJson(e as Map<String, dynamic>))
          .toList(),
    );
  }
}
