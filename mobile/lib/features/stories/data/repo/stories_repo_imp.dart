import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/safwests_model/safwest_model.dart';
import 'package:hawdaj/features/stories/data/repo/stories_repo.dart';

class StoriesRepoImp implements StoriesRepo {
  final ApiConsumer apiConsumer;

  StoriesRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, SafwestModel>> fetchStories(String slug) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.details(slug)),
      (data) => SafwestModel.fromJson(data['data']),
    );
  }
}
