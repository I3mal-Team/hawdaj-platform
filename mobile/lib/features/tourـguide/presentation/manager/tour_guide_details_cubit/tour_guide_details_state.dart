part of 'tour_guide_details_cubit.dart';

sealed class TourGuideDetailsState extends Equatable {
  const TourGuideDetailsState();

  @override
  List<Object> get props => [];
}

final class TourGuideDetailsStateInitial extends TourGuideDetailsState {}

final class TourGuideDetailsStateLoading extends TourGuideDetailsState {}

final class TourGuideDetailsStateSuccess extends TourGuideDetailsState {
  final GuideModel tourGuide;
  const TourGuideDetailsStateSuccess(this.tourGuide);
}

final class TourGuideDetailsStateError extends TourGuideDetailsState {
  final String errMessage;
  const TourGuideDetailsStateError(this.errMessage);
}
