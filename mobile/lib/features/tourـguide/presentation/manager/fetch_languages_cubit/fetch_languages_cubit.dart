import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo.dart';

part 'fetch_languages_state.dart';

class FetchLanguagesCubit extends Cubit<FetchLanguagesState> {
  FetchLanguagesCubit(this.repo) : super(FetchLanguagesInitial());
  final TourGuideRepo repo;

  Future<void> fetchLanguages() async {
    emit(FetchLanguagesLoading());
    final result = await repo.fetchLanguages();
    result.fold(
      (l) => emit(FetchLanguagesFailure(l.errMessage)),
      (r) => emit(FetchLanguagesSuccess(r)),
    );
  }
}
