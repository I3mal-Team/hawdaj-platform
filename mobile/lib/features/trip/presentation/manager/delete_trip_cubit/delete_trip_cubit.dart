import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

part 'delete_trip_state.dart';

class DeleteTripCubit extends Cubit<DeleteTripState> {
  DeleteTripCubit(this.tripRepo) : super(DeleteTripInitial());
  final TripRepo tripRepo;

  Future<void> deleteTrip(final String token) async {
    emit(DeleteTripLoading());
    final result = await tripRepo.deleteTrip(token);
    result.fold(
      (l) => emit(DeleteTripError(l.errMessage)),
      (r) => emit(DeleteTripSuccess(r)),
    );
  }
}
