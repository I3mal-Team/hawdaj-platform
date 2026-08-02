import 'package:flutter_bloc/flutter_bloc.dart';

import 'package:hawdaj/features/favorites/data/repo/favorite_repo.dart';
import 'favorite_state.dart';

class FavoriteCubit extends Cubit<FavoriteState> {
  final FavoriteRepo repo;

  FavoriteCubit(this.repo) : super(FavoriteInitial());

  Future<void> addToFavorite({
    required int favoriteId,
    required String favoriteType,
  }) async {
    emit(FavoriteLoading());
    final result = await repo.addFavorites(
      favoriteType: favoriteType,
      favoriteId: favoriteId,
    );

    result.fold(
      (failure) => emit(FavoriteError(failure.errMessage)),
      (message) => emit(FavoriteSuccess(message)),
    );
  }
}
