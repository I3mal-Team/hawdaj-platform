import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/zad_model/zad_model.dart';
import 'package:hawdaj/features/stores/data/repo/stores_repo.dart';

class StoresRepoImp implements StoresRepo {
  final ApiConsumer apiConsumer;

  StoresRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, ZadModel>> fetchStores(String slug) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.detailsStores(slug)),
      (data) => ZadModel.fromJson(data['data']),
    );
  }
}
