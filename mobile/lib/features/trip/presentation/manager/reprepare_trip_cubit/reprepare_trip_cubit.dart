import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'reprepare_trip_state.dart';

class ReprepareTripCubit extends Cubit<ReprepareTripState> {
  final TripRepo _repo;

  ReprepareTripCubit(this._repo) : super(ReprepareTripInitial());

  Future<void> reprepareTrip(String token) async {
    emit(ReprepareTripLoading());

    final Either<Failure, EnhancedTripResponse> result = await _repo
        .reprepareTrip(token);

    result.fold(
      (failure) => emit(ReprepareTripFailure(failure.errMessage)),
      (response) => emit(ReprepareTripSuccess(response)),
    );
  }
}
