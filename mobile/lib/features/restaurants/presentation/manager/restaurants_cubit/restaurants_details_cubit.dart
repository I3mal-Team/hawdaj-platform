import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/home/data/model/zad_model/zad_model.dart';
import 'package:hawdaj/features/restaurants/data/repo/restaurants_repo.dart';

part 'restaurants_details_state.dart';

class RestaurantsDetailsCubit extends Cubit<RestaurantsDetailsState> {
  RestaurantsDetailsCubit(this.slug, this.restaurants)
    : super(RestaurantsDetailsInitial());
  final RestaurantsRepo restaurants;
  final String slug;

  Future<void> getRestaurantsInfo() async {
    emit(RestaurantsDetailsLoading());
    final result = await restaurants.fetchRestaurants(slug);
    result.fold(
      (failure) => emit(RestaurantsDetailsError(failure.errMessage)),
      (restaurantsDetails) {
        emit(RestaurantsDetailsSuccess(restaurantsDetails));
      },
    );
  }
}
