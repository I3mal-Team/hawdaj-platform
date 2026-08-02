import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/saved/data/repo/saved_repo.dart';

class SavedRepoImp implements SavedRepo {
  final ApiConsumer apiConsumer;

  SavedRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, String>> addSaved({
    required String savedType,
    required int savedId,
  }) {
    return apiConsumer.handleRequest(
      () => apiConsumer.post(
        EndPoints.saved,
        queryParameters: {'saved_type': savedType, 'saved_id': savedId},
      ),
      (data) => data['message'],
    );
  }
}
