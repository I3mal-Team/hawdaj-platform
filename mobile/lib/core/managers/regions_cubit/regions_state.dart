part of 'regions_cubit.dart';

sealed class RegionsState extends Equatable {
  const RegionsState();

  @override
  List<Object> get props => [];
}

final class RegionsInitial extends RegionsState {}

final class RegionsLoading extends RegionsState {}

final class RegionsSuccess extends RegionsState {
  final List<RegionModel> regions;

  const RegionsSuccess(this.regions);

  @override
  List<Object> get props => [regions];
}

final class RegionsError extends RegionsState {
  final String errMessage;

  const RegionsError(this.errMessage);

  @override
  List<Object> get props => [errMessage];
}
