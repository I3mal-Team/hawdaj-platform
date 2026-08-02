part of 'restaurants_details_cubit.dart';

sealed class RestaurantsDetailsState extends Equatable {
  const RestaurantsDetailsState();

  @override
  List<Object> get props => [];
}

final class RestaurantsDetailsInitial extends RestaurantsDetailsState {}

final class RestaurantsDetailsLoading extends RestaurantsDetailsState {}

final class RestaurantsDetailsSuccess extends RestaurantsDetailsState {
  final ZadModel RestaurantsDetails;
  const RestaurantsDetailsSuccess(this.RestaurantsDetails);
}

final class RestaurantsDetailsError extends RestaurantsDetailsState {
  final String errMessage;
  const RestaurantsDetailsError(this.errMessage);
}
