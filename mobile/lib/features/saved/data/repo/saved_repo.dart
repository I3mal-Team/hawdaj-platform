import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';

abstract class SavedRepo {
  Future<Either<Failure, String>> addSaved({
    required String savedType,
    required int savedId,
  });
}
