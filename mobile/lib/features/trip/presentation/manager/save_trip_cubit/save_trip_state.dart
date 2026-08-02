part of 'save_trip_cubit.dart';

sealed class SaveTripState extends Equatable {
  const SaveTripState();

  @override
  List<Object?> get props => [];
}

final class SaveTripInitial extends SaveTripState {}

final class SaveTripLoading extends SaveTripState {
  const SaveTripLoading();
}

final class SaveTripSuccess extends SaveTripState {
  final String message;
  const SaveTripSuccess(this.message);

  @override
  List<Object?> get props => [message];
}

final class SaveTripError extends SaveTripState {
  final String message;
  final int? statusCode;
  final String? errorType;

  const SaveTripError(this.message, {this.statusCode, this.errorType});

  @override
  List<Object?> get props => [message, statusCode, errorType];
}
