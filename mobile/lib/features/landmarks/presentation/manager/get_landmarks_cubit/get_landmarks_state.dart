part of 'get_landmarks_cubit.dart';

abstract class GetLandmarksState extends Equatable {
  const GetLandmarksState();

  @override
  List<Object?> get props => [];
}

class GetLandmarksInitial extends GetLandmarksState {}

class GetLandmarksLoaded extends GetLandmarksState {
  final PagingController<int, LandmarkItem> pagingController;

  const GetLandmarksLoaded(this.pagingController);

  @override
  List<Object?> get props => [pagingController];
}

class GetLandmarksError extends GetLandmarksState {
  final String message;

  const GetLandmarksError(this.message);

  @override
  List<Object?> get props => [message];
}
