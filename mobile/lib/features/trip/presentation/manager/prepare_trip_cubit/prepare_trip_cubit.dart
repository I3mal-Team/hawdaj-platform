import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/data/model/new_trip_prepare_request.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/prepare_trip_model.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/trip_model.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'prepare_trip_state.dart';

class PrepareTripCubit extends Cubit<PrepareTripState> {
  final TripRepo tripRepo;
  PrepareTripCubit(this.tripRepo) : super(PrepareTripInitial());

  Future<void> prepareTrip({
    required PrepareTripModel modelV1,
    required NewTripPrepareParams modelV2,
    required bool useNew,
  }) async {
    emit(PrepareTripLoading());
    print('Preparing trip with API ${useNew ? "v2" : "v1"}');

    if (useNew) {
      final result = await tripRepo.newPrepareTrip(modelV2);
      result.fold((failure) => emit(PrepareTripFailure(failure.errMessage)), (
        enhanced,
      ) {
        print("------->enhanced token : ${enhanced.data?.token}");
        emit(PrepareTripSuccess(enhanced: enhanced));
      });
    } else {
      final result = await tripRepo.prepareTrip(modelV1);
      result.fold(
        (failure) => emit(PrepareTripFailure(failure.errMessage)),
        (trip) => emit(PrepareTripSuccess(trip: trip)),
      );
    }
  }
}
