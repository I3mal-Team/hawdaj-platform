part of 'prepare_trip_cubit.dart';

abstract class PrepareTripState extends Equatable {
  const PrepareTripState();

  @override
  List<Object?> get props => [];
}

class PrepareTripInitial extends PrepareTripState {}

class PrepareTripLoading extends PrepareTripState {}

class PrepareTripSuccess extends PrepareTripState {
  final TripModel? trip;
  final EnhancedTripResponse? enhanced;

  const PrepareTripSuccess({this.trip, this.enhanced});

  @override
  List<Object?> get props => [trip, enhanced];
}

class PrepareTripFailure extends PrepareTripState {
  final String message;
  const PrepareTripFailure(this.message);

  @override
  List<Object?> get props => [message];
}
