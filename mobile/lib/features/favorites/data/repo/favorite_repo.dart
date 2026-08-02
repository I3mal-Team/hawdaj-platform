import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';

abstract class FavoriteRepo {
  Future<Either<Failure, String>> addFavorites({
    required String favoriteType,
    required int favoriteId,
  });
}
