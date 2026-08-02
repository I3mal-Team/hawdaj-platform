import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/landmarks/data/models/list_landmark_response.dart';
import 'package:hawdaj/features/landmarks/data/repositories/landmarks_repository_repo.dart';

part 'landmark_show_state.dart';

class LandmarkShowCubit extends Cubit<LandmarkShowState> {
  LandmarkShowCubit(this.repository) : super(LandmarkShowInitial());
  final LandmarksRepository repository;

  Future<void> getLandmark(int id) async {
    emit(LandmarkShowLoading());
    final result = await repository.getLandmark(id);
    result.fold(
      (failure) => emit(LandmarkShowError(failure.errMessage)),
      (landmark) => emit(LandmarkShowLoaded(landmark)),
    );
  }
}
