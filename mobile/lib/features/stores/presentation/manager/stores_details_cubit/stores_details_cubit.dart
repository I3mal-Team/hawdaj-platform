import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/home/data/model/zad_model/zad_model.dart';
import 'package:hawdaj/features/stores/data/repo/stores_repo.dart';

part 'stores_details_state.dart';

class StoresDetailsCubit extends Cubit<StoresDetailsState> {
  StoresDetailsCubit(this.storesRepo, this.slug)
    : super(StoresDetailsInitial());
  final StoresRepo storesRepo;
  final String slug;

  Future<void> getStoresInfo() async {
    emit(StoresDetailsLoading());
    final result = await storesRepo.fetchStores(slug);
    result.fold((failure) => emit(StoresDetailsError(failure.errMessage)), (
      storesDetails,
    ) {
      emit(StoresDetailsSuccess(storesDetails));
    });
  }
}
