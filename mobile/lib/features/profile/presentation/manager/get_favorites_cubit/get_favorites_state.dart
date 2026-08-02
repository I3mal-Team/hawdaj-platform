part of 'get_favorites_cubit.dart';

sealed class GetFavoritesState extends Equatable {
  const GetFavoritesState();

  @override
  List<Object> get props => [];
}

final class GetFavoritesInitial extends GetFavoritesState {}

final class GetFavoritesLoading extends GetFavoritesState {}

final class GetFavoritesSuccess extends GetFavoritesState {
  final List<FavoriteModel> favorites;
  const GetFavoritesSuccess(this.favorites);
  @override
  List<Object> get props => [favorites];
}

final class GetFavoritesFailure extends GetFavoritesState {
  final String errMessage;
  const GetFavoritesFailure(this.errMessage);
  @override
  List<Object> get props => [errMessage];
}
