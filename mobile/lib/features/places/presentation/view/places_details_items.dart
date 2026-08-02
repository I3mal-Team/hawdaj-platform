import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import 'package:hawdaj/features/places/presentation/manager/cubit/place_details_cubit.dart';
import 'package:hawdaj/features/places/presentation/view/widgets/places_details_items_body.dart';

class PlacesDetailsItems extends StatelessWidget {
  const PlacesDetailsItems({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocBuilder<PlaceDetailsCubit, PlaceDetailsState>(
        builder: (context, state) {
          if (state is PlaceDetailsLoading) {
            return const Center(child: CircularProgressIndicator());
          } else if (state is PlaceDetailsError) {
            return Center(child: Text(state.errMessage));
          } else if (state is PlaceDetailsSuccess) {
            final place = state.placeDetails;

            return PlacesDetailsItemsBody(place: place);
          } else {
            return const SizedBox(); // حالة مبدئية (Initial)
          }
        },
      ),
    );
  }
}
