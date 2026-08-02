import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/user_stories/data/models/user_stories_response.dart';

abstract class UserStoriesState extends Equatable {
  const UserStoriesState();

  @override
  List<Object?> get props => [];
}

class UserStoriesInitial extends UserStoriesState {}

class UserStoriesLoading extends UserStoriesState {}

class UserStoriesLoaded extends UserStoriesState {
  final UserStoriesResponse userStoriesResponse;

  const UserStoriesLoaded(this.userStoriesResponse);

  @override
  List<Object?> get props => [userStoriesResponse];
}

class UserStoriesError extends UserStoriesState {
  final String message;

  const UserStoriesError(this.message);

  @override
  List<Object?> get props => [message];
}
