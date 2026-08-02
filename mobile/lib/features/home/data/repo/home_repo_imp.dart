import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/home_response.dart';
import 'package:hawdaj/features/home/data/model/slider_model.dart';
import 'package:hawdaj/features/home/data/repo/home_repo.dart';

class HomeRepoImp implements HomeRepo {
  final ApiConsumer apiConsumer;

  HomeRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, HomeResponse>> fetchHome() {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.home),
      (data) => HomeResponse.fromJson(data['data']),
    );
  }

  @override
  Future<Either<Failure, List<SliderModel>>> fetchSliders() {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.sliders),
      (data) => (data['data'] as List)
          .map((e) => SliderModel.fromJson(e))
          .toList(),
    );
  }
}
