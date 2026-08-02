part of 'events_details_cubit.dart';

sealed class EventsDetailsState extends Equatable {
  const EventsDetailsState();

  @override
  List<Object> get props => [];
}

final class EventsDetailsInitial extends EventsDetailsState {}

final class EventsDetailsLoading extends EventsDetailsState {}

final class EventsDetailsSuccess extends EventsDetailsState {
  final EventModel eventsDetails;
  const EventsDetailsSuccess(this.eventsDetails);
}

final class EventsDetailsError extends EventsDetailsState {
  final String errMessage;
  const EventsDetailsError(this.errMessage);
}
