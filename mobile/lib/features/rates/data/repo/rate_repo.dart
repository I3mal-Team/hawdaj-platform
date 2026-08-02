import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';

abstract class RateRepo {
  Future<Either<Failure, String>> addRate({
    required String name,
    required int rate,
    required String email,
    required String rateText,
    required String type,
    required String parentId,
  });
}
