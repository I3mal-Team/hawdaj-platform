import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/trip/data/model/trip_details.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'view_trip_state.dart';

class ViewTripCubit extends Cubit<ViewTripState> {
  ViewTripCubit(this.tripRepo, this.token) : super(ViewTripInitial());
  final TripRepo tripRepo;
  final String token;

  Future<void> viewTrip() async {
    emit(ViewTripLoading());
    final failureOrTripDetails = await tripRepo.viewTrip(token);
    failureOrTripDetails.fold(
      (failure) => emit(ViewTripError(failure)),
      (tripDetails) => emit(ViewTripLoaded(tripDetails)),
    );
  }
}
