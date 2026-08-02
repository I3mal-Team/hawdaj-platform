import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/paginated_response.dart';
import 'package:hawdaj/features/home/data/model/zad_model/zad_model.dart';
import 'package:hawdaj/features/restaurants/data/model/menu_item_model.dart';
import 'package:hawdaj/features/restaurants/data/model/offer_item_model.dart';

abstract class RestaurantsRepo {
  Future<Either<Failure, ZadModel>> fetchRestaurants(String slug);
  Future<Either<Failure, PaginatedResponse<OfferItemModel>>> fetchOffers(
    int id,
    int page,
  );
  Future<Either<Failure, PaginatedResponse<MenuItemModel>>> fetchMenu(
    int id,
    int page,
  );
}
