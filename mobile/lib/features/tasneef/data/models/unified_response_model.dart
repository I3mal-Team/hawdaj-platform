import 'laravel_pagination_model.dart';
import 'unified_place_model.dart';

class UnifiedResponseModel {
  final int code;
  final String message;
  final LaravelPaginationModel<UnifiedPlaceModel> data;

  UnifiedResponseModel({
    required this.code,
    required this.message,
    required this.data,
  });

  factory UnifiedResponseModel.fromJson(Map<String, dynamic> json) {
    return UnifiedResponseModel(
      code: json['code'] ?? 200,
      message: json['message'] ?? 'success',
      data: LaravelPaginationModel.fromJson(
        json['data'] ?? {},
        (itemJson) => UnifiedPlaceModel.fromJson(itemJson),
      ),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'code': code,
      'message': message,
      'data': data.toJson((place) => place.toJson()),
    };
  }
}
