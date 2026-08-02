part of 'new_view_trip_cubit.dart';

abstract class NewViewTripState extends Equatable {
  const NewViewTripState();

  @override
  List<Object?> get props => [];
}

class NewViewTripInitial extends NewViewTripState {}

class NewViewTripLoading extends NewViewTripState {}

class NewViewTripSuccess extends NewViewTripState {
  final EnhancedTripResponse response;
  const NewViewTripSuccess(this.response);

  @override
  List<Object?> get props => [response];
}

class NewViewTripFailure extends NewViewTripState {
  final String message;
  const NewViewTripFailure(this.message);

  @override
  List<Object?> get props => [message];
}
