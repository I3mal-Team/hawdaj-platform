import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/places/data/repo/places_repo.dart';

part 'place_details_state.dart';

class PlaceDetailsCubit extends Cubit<PlaceDetailsState> {
  PlaceDetailsCubit(this.placesRepo, this.slug) : super(PlaceDetailsInitial());
  final PlacesRepo placesRepo;
  final String slug;

  Future<void> getPlaceInfo() async {
    emit(PlaceDetailsLoading());
    final result = await placesRepo.fetchPlace(slug);
    result.fold((failure) => emit(PlaceDetailsError(failure.errMessage)), (
      placeDetails,
    ) {
      emit(PlaceDetailsSuccess(placeDetails));
    });
  }
}
