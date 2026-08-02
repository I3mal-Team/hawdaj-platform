part of 'delete_trip_cubit.dart';

sealed class DeleteTripState extends Equatable {
  const DeleteTripState();

  @override
  List<Object> get props => [];
}

final class DeleteTripInitial extends DeleteTripState {}

final class DeleteTripLoading extends DeleteTripState {}

final class DeleteTripSuccess extends DeleteTripState {
  final String message;
  const DeleteTripSuccess(this.message);
}

final class DeleteTripError extends DeleteTripState {
  final String message;
  const DeleteTripError(this.message);
}
