import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo.dart';

part 'tour_guide_details_state.dart';

class TourGuideDetailsCubit extends Cubit<TourGuideDetailsState> {
  TourGuideDetailsCubit(this.id, this.tourGuideRepo)
    : super(TourGuideDetailsStateInitial());
  final TourGuideRepo tourGuideRepo;
  final int id;

  Future<void> getTourGuideInfo() async {
    emit(TourGuideDetailsStateLoading());
    final result = await tourGuideRepo.fetchTourGuide(id);
    result.fold(
      (failure) => emit(TourGuideDetailsStateError(failure.errMessage)),
      (TourGuideDetails) {
        emit(TourGuideDetailsStateSuccess(TourGuideDetails));
      },
    );
  }
}
