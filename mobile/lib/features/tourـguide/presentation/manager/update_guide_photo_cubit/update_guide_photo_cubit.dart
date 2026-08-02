import 'dart:io';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo.dart';

part 'update_guide_photo_state.dart';

class UpdateGuidePhotoCubit extends Cubit<UpdateGuidePhotoState> {
  UpdateGuidePhotoCubit(this.tourGuideRepo) : super(UpdateGuidePhotoInitial());
  final TourGuideRepo tourGuideRepo;

  Future<void> updateGuidePhoto({required File? image}) async {
    emit(UpdateGuidePhotoLoading());
    final result = await tourGuideRepo.updateGuidePhoto(image: image);
    result.fold(
      (failure) => emit(UpdateGuidePhotoFailure(failure.errMessage)),
      (image) => emit(UpdateGuidePhotoSuccess(image)),
    );
  }
}
