import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/events/presentation/manager/events_details_cubit/events_details_cubit.dart';
import 'package:hawdaj/features/events/presentation/view/widgets/events_details_items_body.dart';

class EventsDetailsView extends StatelessWidget {
  const EventsDetailsView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocBuilder<EventsDetailsCubit, EventsDetailsState>(
        builder: (context, state) {
          if (state is EventsDetailsLoading) {
            return const Center(child: CircularProgressIndicator());
          } else if (state is EventsDetailsError) {
            return Center(child: Text(state.errMessage));
          } else if (state is EventsDetailsSuccess) {
            final Events = state.eventsDetails;

            return EventsDetailsItemsBody(event: Events);
          } else {
            return const SizedBox();
          }
        },
      ),
    );
  }
}
