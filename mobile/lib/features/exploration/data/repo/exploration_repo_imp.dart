import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/exploration/data/model/glodal_search/item.dart';
import 'package:hawdaj/features/exploration/data/repo/exploration_repo.dart';
import 'package:hawdaj/features/home/data/model/paginated_response.dart';

class ExplorationRepoImp implements ExplorationRepo {
  final ApiConsumer apiConsumer;

  ExplorationRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, PaginatedResponse<ItemGlodal>>> getSearchGlobal(
    int page,
    String? search,
    int? regionId,
    int? cityId,
    String? viewAs,
  ) {
    final Map<String, dynamic> queryParameters = {'page': page, 'per_page': 50};

    if (search != null && search.isNotEmpty) {
      queryParameters['search'] = search;
    }
    if (regionId != null && regionId != 0) {
      queryParameters['region_id'] = regionId;
    }
    if (cityId != null && cityId != 0) {
      queryParameters['city_id'] = cityId;
    }
    if (viewAs != null && viewAs.isNotEmpty) {
      queryParameters['viewAs'] = viewAs;
    }

    return apiConsumer.handleRequest(
      () => apiConsumer.get(
        EndPoints.searchGlobal(page),
        queryParameters: queryParameters,
      ),
      (data) => PaginatedResponse<ItemGlodal>.fromJson(
        data['data'],
        (json) => ItemGlodal.fromJson(json),
      ),
    );
  }
}
