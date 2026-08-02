import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/core/repositories/cities_repository.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';

class CitiesRepositoryImpl implements CitiesRepository {
  final ApiConsumer apiConsumer;

  CitiesRepositoryImpl({required this.apiConsumer});

  @override
  Future<Either<Failure, List<CityModel>>> fetchCitiesByRegion(
    int regionId,
  ) async {
    print('🔍 CitiesRepository: fetchCitiesByRegion($regionId) called');
    final endpoint = EndPoints.citiesByRegion(regionId);
    print('🔍 CitiesRepository: Making API call to $endpoint');

    return apiConsumer.handleRequest(() => apiConsumer.get(endpoint), (
      response,
    ) {
      print('🔍 CitiesRepository: API response received: $response');
      final cities = (response['data'] as List)
          .map((json) => CityModel.fromJson(json))
          .toList();
      print('🔍 CitiesRepository: Parsed ${cities.length} cities');
      return cities;
    });
  }
}
