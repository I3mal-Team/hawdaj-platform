import 'package:equatable/equatable.dart';

abstract class AddStoryState extends Equatable {
  const AddStoryState();

  @override
  List<Object?> get props => [];
}

class AddStoryInitial extends AddStoryState {}

class AddStoryLoading extends AddStoryState {}

class AddStorySuccess extends AddStoryState {
  final String message;

  const AddStorySuccess(this.message);

  @override
  List<Object?> get props => [message];
}

class AddStoryError extends AddStoryState {
  final String message;

  const AddStoryError(this.message);

  @override
  List<Object?> get props => [message];
}
