import 'package:hawdaj/core/errors/failure.dart';
import 'package:dartz/dartz.dart';

abstract class LocationRepository {
  Future<Either<Failure, void>> updateLocation({
    required double lat,
    required double lng,
  });
}
