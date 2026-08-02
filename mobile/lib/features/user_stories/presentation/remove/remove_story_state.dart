part of 'remove_story_cubit.dart';

abstract class RemoveStoryState extends Equatable {
  const RemoveStoryState();

  @override
  List<Object> get props => [];
}

class RemoveStoryInitial extends RemoveStoryState {}

class RemoveStoryLoading extends RemoveStoryState {}

class RemoveStorySuccess extends RemoveStoryState {
  final String message;

  const RemoveStorySuccess(this.message);

  @override
  List<Object> get props => [message];
}

class RemoveStoryError extends RemoveStoryState {
  final String error;

  const RemoveStoryError(this.error);

  @override
  List<Object> get props => [error];
}
