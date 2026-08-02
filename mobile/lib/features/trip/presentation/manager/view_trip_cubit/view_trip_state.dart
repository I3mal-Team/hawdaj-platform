part of 'view_trip_cubit.dart';

sealed class ViewTripState extends Equatable {
  const ViewTripState();

  @override
  List<Object> get props => [];
}

final class ViewTripInitial extends ViewTripState {}

final class ViewTripLoading extends ViewTripState {}

final class ViewTripLoaded extends ViewTripState {
  final TripDetailsModel trip;
  const ViewTripLoaded(this.trip);
  @override
  List<Object> get props => [trip];
}

final class ViewTripError extends ViewTripState {
  final Failure failure;
  const ViewTripError(this.failure);
  @override
  List<Object> get props => [failure];
}
