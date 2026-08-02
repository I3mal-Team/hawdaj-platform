import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';

abstract class RegionsRepository {
  Future<Either<Failure, List<RegionModel>>> fetchRegions();
}
