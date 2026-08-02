import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/favorites/data/repo/favorite_repo.dart';

class FavoriteRepoImp implements FavoriteRepo {
  final ApiConsumer apiConsumer;

  FavoriteRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, String>> addFavorites({
    required String favoriteType,
    required int favoriteId,
  }) {
    return apiConsumer.handleRequest(
      () => apiConsumer.post(
        EndPoints.addFavorites,
        queryParameters: {
          'favoritable_type': favoriteType,
          'favoritable_id': favoriteId,
        },
      ),
      (data) => data['message'],
    );
  }
}
