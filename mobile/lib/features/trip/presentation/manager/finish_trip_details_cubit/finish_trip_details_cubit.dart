import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/prepare_trip_data.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'finish_trip_details_state.dart';

class FinishTripDetailsCubit extends Cubit<FinishTripDetailsState> {
  FinishTripDetailsCubit(this.tripRepo, this.token)
    : super(FinishTripDetailsInitial());
  final TripRepo tripRepo;
  final String token;

  Future<void> finishTripDetails() async {
    emit(FinishTripDetailsLoading());
    final result = await tripRepo.finishTripDetails(token);
    result.fold(
      (failure) => emit(FinishTripDetailsError(failure.errMessage)),
      (trip) => emit(FinishTripDetailsSuccess(trip)),
    );
  }
}
