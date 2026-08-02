import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'new_view_trip_state.dart';

class NewViewTripCubit extends Cubit<NewViewTripState> {
  final TripRepo tripRepository;
  final String token;

  NewViewTripCubit(this.tripRepository, this.token)
    : super(NewViewTripInitial());

  Future<void> newViewTrip() async {
    emit(NewViewTripLoading());

    final result = await tripRepository.newViewTrip(token);

    result.fold(
      (failure) => emit(NewViewTripFailure(failure.errMessage)),
      (response) => emit(NewViewTripSuccess(response)),
    );
  }
}
