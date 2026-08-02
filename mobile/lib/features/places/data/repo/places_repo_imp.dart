import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/places/data/repo/places_repo.dart';

class PlacesRepoImp implements PlacesRepo {
  final ApiConsumer apiConsumer;

  PlacesRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, UnifiedPlaceModel>> fetchPlace(String slug) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.detailsPlaces(slug)),
      (data) => UnifiedPlaceModel.fromJson(data['data']),
    );
  }

  Future<Either<Failure, List<UnifiedPlaceModel>>> fetchZads({
    int? page,
    int? perPage,
    String? categories,
    bool? showNearest,
  }) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(
        EndPoints.zads,
        queryParameters: {
          if (page != null) 'page': page,
          if (perPage != null) 'per_page': perPage,
          if (categories != null) 'categories': categories,
          if (showNearest != null) 'showNearest': showNearest,
        },
      ),
      (data) {
        final items = data['data']['data'] as List<dynamic>;
        return items.map((item) => UnifiedPlaceModel.fromJson(item)).toList();
      },
    );
  }
}
