part of 'fetch_all_tour_guide_cubit.dart';

sealed class FetchAllTourGuideState extends Equatable {
  const FetchAllTourGuideState();

  @override
  List<Object> get props => [];
}

final class FetchAllTourGuideInitial extends FetchAllTourGuideState {}

final class FetchAllTourGuideLoading extends FetchAllTourGuideState {}

final class FetchAllTourGuideError extends FetchAllTourGuideState {
  final String errMessage;
  const FetchAllTourGuideError(this.errMessage);
}

final class FetchAllTourGuideLoaded extends FetchAllTourGuideState {
  final GuideModel tourGuides;
  const FetchAllTourGuideLoaded(this.tourGuides);
}
