part of 'landmark_show_cubit.dart';

sealed class LandmarkShowState extends Equatable {
  const LandmarkShowState();

  @override
  List<Object> get props => [];
}

final class LandmarkShowInitial extends LandmarkShowState {}

final class LandmarkShowLoading extends LandmarkShowState {}

final class LandmarkShowError extends LandmarkShowState {
  final String message;

  const LandmarkShowError(this.message);
}

final class LandmarkShowLoaded extends LandmarkShowState {
  final LandmarkItem landmarkItem;

  const LandmarkShowLoaded(this.landmarkItem);
}
