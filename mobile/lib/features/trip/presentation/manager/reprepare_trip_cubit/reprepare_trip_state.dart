part of 'reprepare_trip_cubit.dart';

abstract class ReprepareTripState {}

class ReprepareTripInitial extends ReprepareTripState {}

class ReprepareTripLoading extends ReprepareTripState {}

class ReprepareTripSuccess extends ReprepareTripState {
  final EnhancedTripResponse trip;

  ReprepareTripSuccess(this.trip);
}

class ReprepareTripFailure extends ReprepareTripState {
  final String message;

  ReprepareTripFailure(this.message);
}
