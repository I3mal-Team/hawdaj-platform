import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/user_stories/data/repositories/user_stories_repository.dart';

part 'remove_story_state.dart';

class RemoveStoryCubit extends Cubit<RemoveStoryState> {
  final UserStoriesRepository repository;

  RemoveStoryCubit(this.repository) : super(RemoveStoryInitial());

  Future<void> removeStory(int storyId) async {
    emit(RemoveStoryLoading());
    final result = await repository.removeStory(storyId: storyId);

    result.fold(
      (failure) => emit(RemoveStoryError(failure.errMessage)),
      (message) => emit(RemoveStorySuccess(message)),
    );
  }
}
