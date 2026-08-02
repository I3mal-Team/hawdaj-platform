part of 'new_my_trip_cubit.dart';

abstract class NewMyTripState extends Equatable {
  const NewMyTripState();

  @override
  List<Object?> get props => [];
}

class NewMyTripInitial extends NewMyTripState {}

class NewMyTripLoaded extends NewMyTripState {
  final PagingController<int, TripItem> pagingController;
  const NewMyTripLoaded(this.pagingController);

  @override
  List<Object?> get props => [pagingController];
}

class NewMyTripError extends NewMyTripState {
  final String message;
  const NewMyTripError(this.message);

  @override
  List<Object?> get props => [message];
}
