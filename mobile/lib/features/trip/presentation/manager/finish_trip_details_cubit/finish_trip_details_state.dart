part of 'finish_trip_details_cubit.dart';

sealed class FinishTripDetailsState extends Equatable {
  const FinishTripDetailsState();

  @override
  List<Object> get props => [];
}

final class FinishTripDetailsInitial extends FinishTripDetailsState {}

final class FinishTripDetailsLoading extends FinishTripDetailsState {}

final class FinishTripDetailsError extends FinishTripDetailsState {
  final String message;
  const FinishTripDetailsError(this.message);

  @override
  List<Object> get props => [message];
}

final class FinishTripDetailsSuccess extends FinishTripDetailsState {
  final TripData trip;
  const FinishTripDetailsSuccess(this.trip);

  @override
  List<Object> get props => [trip];
}
