// lib/features/stories/presentation/manager/stories_details_cubit/stories_details_state.dart
part of 'stories_details_cubit.dart';

sealed class StoriesDetailsState extends Equatable {
  const StoriesDetailsState();

  @override
  List<Object?> get props => [];
}

final class StoriesDetailsInitial extends StoriesDetailsState {}

final class StoriesDetailsLoading extends StoriesDetailsState {}

final class StoriesDetailsSuccess extends StoriesDetailsState {
  final SafwestModel storiesDetails;
  const StoriesDetailsSuccess(this.storiesDetails);

  @override
  List<Object?> get props => [storiesDetails];
}

final class StoriesDetailsError extends StoriesDetailsState {
  final String errMessage;
  const StoriesDetailsError(this.errMessage);

  @override
  List<Object?> get props => [errMessage];
}
