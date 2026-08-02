import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_response_model.dart';
import 'package:hawdaj/features/tasneef/data/models/category_response_model.dart';

abstract class TasneefRepository {
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
  });

  Future<Either<Failure, CategoryResponseModel>> getCategories({
    required String endpoint,
  });

  Future<Either<Failure, CategoryResponseModel>> getSubCategories({
    required String endpoint,
  });
}
