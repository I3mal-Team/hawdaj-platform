import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/exploration/data/model/glodal_search/item.dart';
import 'package:hawdaj/features/home/data/model/paginated_response.dart';

abstract class ExplorationRepo {
  Future<Either<Failure, PaginatedResponse<ItemGlodal>>> getSearchGlobal(
    int page,
    String? search,
    int? regionId,
    int? cityId,
    String? viewAs,
  );
}
