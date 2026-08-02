import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo.dart'
    show TourGuideRepo;

part 'fetch_all_tour_guide_state.dart';

class FetchAllTourGuideCubit extends Cubit<FetchAllTourGuideState> {
  FetchAllTourGuideCubit(this.repo) : super(FetchAllTourGuideInitial());
  final TourGuideRepo repo;

  Future<void> fetchAllTourGuide() async {
    emit(FetchAllTourGuideLoading());
    final either = await repo.fetchAllTourGuide();
    either.fold(
      (l) => emit(FetchAllTourGuideError(l.errMessage)),
      (r) => emit(FetchAllTourGuideLoaded(r)),
    );
  }
}
