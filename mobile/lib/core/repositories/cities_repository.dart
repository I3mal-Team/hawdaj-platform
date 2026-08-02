import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';

abstract class CitiesRepository {
  Future<Either<Failure, List<CityModel>>> fetchCitiesByRegion(int regionId);
}
