import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/profile/data/model/story_model.dart';
import 'package:hawdaj/features/profile/data/repo/profail_repo.dart';
import 'package:hawdaj/features/stories/data/repo/stories_repo.dart';
import 'my_last_day_stories_state.dart';

class MyLastDayStoriesCubit extends Cubit<MyLastDayStoriesState> {
  final ProfileRepo profileRepo;

  MyLastDayStoriesCubit(this.profileRepo) : super(MyLastDayStoriesInitial());

  Future<void> getMyLastDayStories() async {
    emit(MyLastDayStoriesLoading());

    final Either<Failure, List<StoryModel>> result = await profileRepo
        .myLastDayStories();

    result.fold(
      (failure) => emit(MyLastDayStoriesFailure(failure.errMessage)),
      (stories) => emit(MyLastDayStoriesSuccess(stories)),
    );
  }
}
