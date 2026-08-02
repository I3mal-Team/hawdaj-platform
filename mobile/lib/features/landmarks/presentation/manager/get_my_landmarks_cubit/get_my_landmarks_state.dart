part of 'get_my_landmarks_cubit.dart';

sealed class GetMyLandmarksState extends Equatable {
  const GetMyLandmarksState();

  @override
  List<Object> get props => [];
}

final class GetMyLandmarksInitial extends GetMyLandmarksState {}

class GetMyLandmarksLoaded extends GetMyLandmarksState {
  final PagingController<int, LandmarkItem> pagingController;

  const GetMyLandmarksLoaded(this.pagingController);

  @override
  List<Object> get props => [pagingController];
}

class GetMyLandmarksError extends GetMyLandmarksState {
  final String message;

  const GetMyLandmarksError(this.message);

  @override
  List<Object> get props => [message];
}
