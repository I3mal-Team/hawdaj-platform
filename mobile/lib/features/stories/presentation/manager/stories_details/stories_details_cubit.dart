// lib/features/stories/presentation/manager/stories_details_cubit/stories_details_cubit.dart
import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/home/data/model/safwests_model/safwest_model.dart';
import 'package:hawdaj/features/stories/data/repo/stories_repo.dart';

part 'stories_details_state.dart';

class StoriesDetailsCubit extends Cubit<StoriesDetailsState> {
  StoriesDetailsCubit(this.slug, this.storiesRepo)
    : super(StoriesDetailsInitial());

  final StoriesRepo storiesRepo;
  final String slug;

  Future<void> getStoriesInfo() async {
    emit(StoriesDetailsLoading());
    final result = await storiesRepo.fetchStories(slug);
    result.fold(
      (failure) => emit(StoriesDetailsError(failure.errMessage)),
      (storiesDetails) => emit(StoriesDetailsSuccess(storiesDetails)),
    );
  }
}
