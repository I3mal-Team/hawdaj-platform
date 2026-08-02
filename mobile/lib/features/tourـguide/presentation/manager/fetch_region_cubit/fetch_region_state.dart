part of 'fetch_region_cubit.dart';

sealed class FetchRegionState extends Equatable {
  const FetchRegionState();

  @override
  List<Object> get props => [];
}

final class FetchRegionInitial extends FetchRegionState {}

final class FetchRegionLoading extends FetchRegionState {}

final class FetchRegionError extends FetchRegionState {
  final String errMessage;
  const FetchRegionError(this.errMessage);
}

final class FetchRegionLoaded extends FetchRegionState {
  final List<RegionModel> region;
  const FetchRegionLoaded(this.region);
}
