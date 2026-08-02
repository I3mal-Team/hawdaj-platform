import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/restaurants/presentation/manager/restaurants_cubit/restaurants_details_cubit.dart';
import 'package:hawdaj/features/restaurants/presentation/view/widgets/restaurants_details_items_body.dart';

class RestaurantsDetailsItems extends StatelessWidget {
  const RestaurantsDetailsItems({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocBuilder<RestaurantsDetailsCubit, RestaurantsDetailsState>(
        builder: (context, state) {
          if (state is RestaurantsDetailsLoading) {
            return const Center(child: CircularProgressIndicator());
          } else if (state is RestaurantsDetailsError) {
            return Center(child: Text(state.errMessage));
          } else if (state is RestaurantsDetailsSuccess) {
            final Restaurants = state.RestaurantsDetails;

            return RestaurantsDetailsItemsBody(restaurants: Restaurants);
          } else {
            return const SizedBox();
          }
        },
      ),
    );
  }
}
