import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_response_model.dart';
import 'package:hawdaj/features/tasneef/data/models/category_response_model.dart';
import '../../../../core/databases/api/api_consumer.dart';
import '../../domain/repositories/tasneef_repository.dart';

class TasneefRepositoryImpl implements TasneefRepository {
  final ApiConsumer apiConsumer;

  TasneefRepositoryImpl({required this.apiConsumer});

  @override
  Future<Either<Failure, UnifiedResponseModel>> getItems({
    required String endpoint,
    int? page,
    int? perPage,
    String? categories,
    bool? showNearest,
    int? categoryId,
    bool? topFeatured,
    bool? topStores,
    int? regionId,
    int? cityId,
    int? languageId,
    bool? topRated,
    String? search,
    bool? isOnline,
    String? dateFrom,
    String? dateTo,
    String? daterange,
    String? addressType,
    double? lat,
    double? lng,
    List<int>? foodCategories,
    required final String type,
  }) async {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(
        endpoint,
        queryParameters: _buildQueryParams(
          type: type,
          page: page,
          perPage: perPage,
          categories: categories,
          showNearest: showNearest,
          categoryId: categoryId,
          topFeatured: topFeatured,
          topStores: topStores,
          regionId: regionId,
          cityId: cityId,
          languageId: languageId,
          topRated: topRated,
          search: search,
          isOnline: isOnline,
          dateFrom: dateFrom,
          dateTo: dateTo,
          daterange: daterange,
          addressType: addressType,
          lat: lat,
          lng: lng,
          foodCategories: foodCategories,
        ),
      ),
      (data) => UnifiedResponseModel.fromJson(data),
    );
  }

  Map<String, dynamic> _buildQueryParams({
    required String type, // أضف هذا المتغير

    int? page,
    int? perPage,
    String? categories,
    bool? showNearest,
    int? categoryId,
    bool? topFeatured,
    bool? topStores,
    int? regionId,
    int? cityId,
    int? languageId,
    bool? topRated,
    String? search,
    bool? isOnline,
    String? dateFrom,
    String? dateTo,
    String? daterange,
    String? addressType,
    double? lat,
    double? lng,
    List<int>? foodCategories,
  }) {
    final params = <String, dynamic>{};

    if (page != null) params['page'] = page;
    if (perPage != null) params['per_page'] = perPage;

    if (type == 'zads') {
      if (categories != null && categories.isNotEmpty) {
        params['categories'] = categories;
      }
    } else {
      if (categoryId != null) params['category_id'] = categoryId;
      if (categories != null && categories.isNotEmpty)
        params['category_id'] = categories; // حسب ما يستخدم باقي الأنواع
    }
    if (showNearest != null) params['showNearest'] = showNearest;
    if (categoryId != null) params['category_id'] = categoryId;
    if (topFeatured != null) params['top_featured'] = topFeatured;
    if (topStores != null) params['top_stores'] = topStores;
    if (regionId != null) params['region_id'] = regionId;
    if (cityId != null) params['city_id'] = cityId;
    if (languageId != null) params['language_id'] = languageId;
    if (topRated != null) params['top_rated'] = topRated;
    if (search != null && search.isNotEmpty) params['search'] = search;
    if (isOnline != null) params['is_online'] = isOnline ? 1 : 0;
    if (dateFrom != null && dateFrom.isNotEmpty) params['date_from'] = dateFrom;
    if (dateTo != null && dateTo.isNotEmpty) params['date_to'] = dateTo;
    if (daterange != null && daterange.isNotEmpty)
      params['daterange'] = daterange;
    if (addressType != null && addressType.isNotEmpty)
      params['address_type'] = addressType;
    if (lat != null) params['lat'] = lat;
    if (lng != null) params['lng'] = lng;
    if (foodCategories != null && foodCategories.isNotEmpty) {
      for (int i = 0; i < foodCategories.length; i++) {
        params['food_categories[$i]'] = foodCategories[i];
      }
    }

    return params;
  }

  @override
  Future<Either<Failure, CategoryResponseModel>> getCategories({
    required String endpoint,
  }) async {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(endpoint),
      (data) => CategoryResponseModel.fromJson(data),
    );
  }

  @override
  Future<Either<Failure, CategoryResponseModel>> getSubCategories({
    required String endpoint,
  }) async {
    // For now identical to getCategories, kept separate for clarity if logic diverges later
    return apiConsumer.handleRequest(
      () => apiConsumer.get(endpoint),
      (data) => CategoryResponseModel.fromJson(data),
    );
  }
}
