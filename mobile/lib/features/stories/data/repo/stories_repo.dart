import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/safwests_model/safwest_model.dart';

abstract class StoriesRepo {
  Future<Either<Failure, SafwestModel>> fetchStories(String slug);
}
