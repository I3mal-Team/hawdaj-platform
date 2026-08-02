part of 'update_guide_photo_cubit.dart';

sealed class UpdateGuidePhotoState extends Equatable {
  const UpdateGuidePhotoState();

  @override
  List<Object> get props => [];
}

final class UpdateGuidePhotoInitial extends UpdateGuidePhotoState {}

final class UpdateGuidePhotoLoading extends UpdateGuidePhotoState {}

final class UpdateGuidePhotoSuccess extends UpdateGuidePhotoState {
  final String image;
  const UpdateGuidePhotoSuccess(this.image);

  @override
  List<Object> get props => [image];
}

final class UpdateGuidePhotoFailure extends UpdateGuidePhotoState {
  final String message;
  const UpdateGuidePhotoFailure(this.message);

  @override
  List<Object> get props => [message];
}
