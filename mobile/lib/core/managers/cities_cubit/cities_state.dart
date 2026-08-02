part of 'cities_cubit.dart';

sealed class CitiesState extends Equatable {
  const CitiesState();

  @override
  List<Object> get props => [];
}

final class CitiesInitial extends CitiesState {}

final class CitiesLoading extends CitiesState {}

final class CitiesSuccess extends CitiesState {
  final List<CityModel> cities;

  const CitiesSuccess(this.cities);

  @override
  List<Object> get props => [cities];
}

final class CitiesError extends CitiesState {
  final String errMessage;

  const CitiesError(this.errMessage);

  @override
  List<Object> get props => [errMessage];
}
