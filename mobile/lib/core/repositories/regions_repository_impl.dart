import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/core/repositories/regions_repository.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';

class RegionsRepositoryImpl implements RegionsRepository {
  final ApiConsumer apiConsumer;

  RegionsRepositoryImpl({required this.apiConsumer});

  @override
  Future<Either<Failure, List<RegionModel>>> fetchRegions() async {
    print('🔍 RegionsRepository: fetchRegions() called');
    print('🔍 RegionsRepository: Making API call to ${EndPoints.regions}');

    return apiConsumer.handleRequest(() => apiConsumer.get(EndPoints.regions), (
      response,
    ) {
      print('🔍 RegionsRepository: API response received: $response');
      final regions = (response['data'] as List)
          .map((json) => RegionModel.fromJson(json))
          .toList();
      print('🔍 RegionsRepository: Parsed ${regions.length} regions');
      return regions;
    });
  }
}
