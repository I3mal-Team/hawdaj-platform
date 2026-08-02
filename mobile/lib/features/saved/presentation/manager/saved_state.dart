import 'package:equatable/equatable.dart';

abstract class SavedState extends Equatable {
  const SavedState();

  @override
  List<Object?> get props => [];
}

class SavedInitial extends SavedState {}

class SavedLoading extends SavedState {}

class SavedSuccess extends SavedState {
  final String message;
  const SavedSuccess(this.message);

  @override
  List<Object?> get props => [message];
}

class SavedError extends SavedState {
  final String error;
  const SavedError(this.error);

  @override
  List<Object?> get props => [error];
}
