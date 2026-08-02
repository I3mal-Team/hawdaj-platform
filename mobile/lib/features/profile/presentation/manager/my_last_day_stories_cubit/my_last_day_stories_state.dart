import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/profile/data/model/story_model.dart';

abstract class MyLastDayStoriesState extends Equatable {
  const MyLastDayStoriesState();

  @override
  List<Object?> get props => [];
}

class MyLastDayStoriesInitial extends MyLastDayStoriesState {}

class MyLastDayStoriesLoading extends MyLastDayStoriesState {}

class MyLastDayStoriesSuccess extends MyLastDayStoriesState {
  final List<StoryModel> stories;

  const MyLastDayStoriesSuccess(this.stories);

  @override
  List<Object?> get props => [stories];
}

class MyLastDayStoriesFailure extends MyLastDayStoriesState {
  final String error;

  const MyLastDayStoriesFailure(this.error);

  @override
  List<Object?> get props => [error];
}
