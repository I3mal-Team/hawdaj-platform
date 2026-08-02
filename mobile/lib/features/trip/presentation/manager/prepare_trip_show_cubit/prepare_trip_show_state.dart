part of 'prepare_trip_show_cubit.dart';

abstract class PrepareTripShowState extends Equatable {
  const PrepareTripShowState();

  @override
  List<Object?> get props => [];
}

class PrepareTripShowInitial extends PrepareTripShowState {}

class PrepareTripShowLoading extends PrepareTripShowState {}

class PrepareTripShowSuccess extends PrepareTripShowState {
  final EnhancedTripResponse response;

  const PrepareTripShowSuccess(this.response);

  PrepareTripShowSuccess copyWith({EnhancedTripResponse? response}) {
    return PrepareTripShowSuccess(response ?? this.response);
  }

  @override
  List<Object?> get props => [response];
}

class PrepareTripShowFailure extends PrepareTripShowState {
  final String message;

  const PrepareTripShowFailure(this.message);

  @override
  List<Object?> get props => [message];
}
