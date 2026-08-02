import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';

abstract class PlacesRepo {
  Future<Either<Failure, UnifiedPlaceModel>> fetchPlace(String slug);
}
