import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/trip/data/model/prices_model/prices_model.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'fetch_prices_state.dart';

class FetchPricesCubit extends Cubit<FetchPricesState> {
  FetchPricesCubit(this.tripRepo) : super(FetchPricesInitial());
  final TripRepo tripRepo;
  Future<void> fetchPrices() async {
    emit(FetchPricesLoading());
    final result = await tripRepo.fetchPrices();
    result.fold(
      (failure) => emit(FetchPricesError(failure.errMessage)),
      (pricesModel) => emit(FetchPricesSuccess(pricesModel)),
    );
  }
}
