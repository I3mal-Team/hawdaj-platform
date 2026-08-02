// save_trip_to_email_state.dart
part of 'save_trip_to_email_cubit.dart';

abstract class SaveTripToEmailState extends Equatable {
  const SaveTripToEmailState();

  @override
  List<Object?> get props => [];
}

class SaveTripToEmailInitial extends SaveTripToEmailState {
  const SaveTripToEmailInitial();
}

class SaveTripToEmailLoading extends SaveTripToEmailState {
  const SaveTripToEmailLoading();
}

class SaveTripToEmailSuccess extends SaveTripToEmailState {
  final String message;
  const SaveTripToEmailSuccess(this.message);

  @override
  List<Object?> get props => [message];
}

class SaveTripToEmailError extends SaveTripToEmailState {
  final String message;
  const SaveTripToEmailError(this.message);

  @override
  List<Object?> get props => [message];
}
