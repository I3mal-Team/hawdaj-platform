import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/home_response.dart';
import 'package:hawdaj/features/home/data/model/slider_model.dart';

abstract class HomeRepo {
  Future<Either<Failure, HomeResponse>> fetchHome();
  Future<Either<Failure, List<SliderModel>>> fetchSliders();
  //
}
