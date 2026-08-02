import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo.dart';

part 'fetch_region_state.dart';

class FetchRegionCubit extends Cubit<FetchRegionState> {
  FetchRegionCubit(this.repo) : super(FetchRegionInitial());
  final TourGuideRepo repo;

  Future<void> fetchRegion() async {
    emit(FetchRegionLoading());
    final either = await repo.fetchRegion();
    either.fold(
      (l) => emit(FetchRegionError(l.errMessage)),
      (r) => emit(FetchRegionLoaded(r)),
    );
  }
}
