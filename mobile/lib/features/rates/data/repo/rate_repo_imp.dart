import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/rates/data/repo/rate_repo.dart';

class RateRepoImp implements RateRepo {
  final ApiConsumer apiConsumer;

  RateRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, String>> addRate({
    required String name,
    required int rate,
    required String email,
    required String rateText,
    required String type,
    required String parentId,
  }) {
    return apiConsumer.handleRequest(
      () => apiConsumer.post(
        EndPoints.addRate,
        queryParameters: {
          'name': name,
          'rate': rate,
          'email': email,
          'rateText': rateText,
          'type': type,
          'parent_id': parentId,
        },
      ),
      (data) => data['message'],
    );
  }
}
