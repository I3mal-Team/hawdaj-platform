import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/zad_model/zad_model.dart';

abstract class StoresRepo {
  Future<Either<Failure, ZadModel>> fetchStores(String slug);
}
