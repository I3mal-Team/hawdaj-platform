import 'package:dartz/dartz.dart';
import 'package:dio/dio.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/core/errors/exceptions.dart';

import 'package:hawdaj/core/repositories/location_repository.dart';

class LocationRepositoryImpl implements LocationRepository {
  final ApiConsumer apiConsumer;

  LocationRepositoryImpl({required this.apiConsumer});

  @override
  Future<Either<Failure, void>> updateLocation({
    required double lat,
    required double lng,
  }) async {
    try {
      print('---->Updating location...');
      await apiConsumer.post(
        EndPoints.updateLocation,
        data: {'lat': lat, 'lng': lng},
      );
      return const Right(null);
    } catch (e) {
      if (e is DioException) {
        return Left(ServerFailure.fromDioException(e));
      }
      return Left(ServerFailure(errMessage: e.toString()));
    }
  }
}
