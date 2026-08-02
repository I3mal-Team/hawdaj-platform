import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/paginated_response.dart';
import 'package:hawdaj/features/home/data/model/zad_model/zad_model.dart';
import 'package:hawdaj/features/restaurants/data/model/menu_item_model.dart';
import 'package:hawdaj/features/restaurants/data/model/offer_item_model.dart';
import 'package:hawdaj/features/restaurants/data/repo/restaurants_repo.dart';

class RestaurantsRepoImp implements RestaurantsRepo {
  final ApiConsumer apiConsumer;
  RestaurantsRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, ZadModel>> fetchRestaurants(String slug) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.detailsRestaurants(slug)),
      (data) => ZadModel.fromJson(data['data']),
    );
  }

  @override
  Future<Either<Failure, PaginatedResponse<OfferItemModel>>> fetchOffers(
    int id,
    int page,
  ) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.offers(id)),
      (data) => PaginatedResponse<OfferItemModel>.fromJson(
        data,
        (json) => OfferItemModel.fromJson(json),
      ),
    );
  }

  Future<Either<Failure, PaginatedResponse<MenuItemModel>>> fetchMenu(
    int id,
    int page,
  ) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.menu(id)),
      (data) => PaginatedResponse<MenuItemModel>.fromJson(
        data,
        (json) => MenuItemModel.fromJson(json),
      ),
    );
  }
}
