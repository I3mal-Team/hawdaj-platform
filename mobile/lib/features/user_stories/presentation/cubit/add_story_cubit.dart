import 'dart:io';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/user_stories/data/repositories/user_stories_repository.dart';
import 'package:hawdaj/features/user_stories/presentation/cubit/add_story_state.dart';

class AddStoryCubit extends Cubit<AddStoryState> {
  final UserStoriesRepository userStoriesRepository;

  AddStoryCubit(this.userStoriesRepository) : super(AddStoryInitial());

  Future<void> addStory({
    String? title,
    required File file,
    required String fileType,
  }) async {
    emit(AddStoryLoading());
    
    final result = await userStoriesRepository.addStory(
      title: title,
      file: file,
      fileType: fileType,
    );
    
    result.fold(
      (failure) => emit(AddStoryError(failure.errMessage)),
      (success) => emit(const AddStorySuccess('تم إضافة القصة بنجاح')),
    );
  }
}
