import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/trip/data/model/prices_model/prices_model.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'fetch_category_state.dart';

class FetchCategoryCubit extends Cubit<FetchCategoryState> {
  FetchCategoryCubit(this.tripRepo) : super(FetchCategoryInitial());
  final TripRepo tripRepo;
  Future<void> fetchCategory() async {
    emit(FetchCategoryLoading());
    final result = await tripRepo.fetchCategory();
    result.fold(
      (failure) => emit(FetchCategoryError(failure.errMessage)),
      (categoryList) => emit(FetchCategorySuccess(categoryList)),
    );
  }
}
