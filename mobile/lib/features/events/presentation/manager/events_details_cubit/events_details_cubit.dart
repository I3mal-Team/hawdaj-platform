import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/events/data/repo/events_repo.dart';
import 'package:hawdaj/features/home/data/model/top_events_model/event_model.dart';

part 'events_details_state.dart';

class EventsDetailsCubit extends Cubit<EventsDetailsState> {
  EventsDetailsCubit(this.slug, this.eventsRepo)
    : super(EventsDetailsInitial());
  final EventsRepo eventsRepo;
  final String slug;

  Future<void> getEventsInfo() async {
    emit(EventsDetailsLoading());
    final result = await eventsRepo.fetchEvents(slug);
    result.fold((failure) => emit(EventsDetailsError(failure.errMessage)), (
      eventsDetails,
    ) {
      emit(EventsDetailsSuccess(eventsDetails));
    });
  }
}
