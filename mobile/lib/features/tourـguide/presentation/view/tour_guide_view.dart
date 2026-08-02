import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/fetch_all_tour_guide_cubit/fetch_all_tour_guide_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/tour_guide_body.dart';

class TourGuideView extends StatelessWidget {
  const TourGuideView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocBuilder<FetchAllTourGuideCubit, FetchAllTourGuideState>(
        builder: (context, state) {
          if (state is FetchAllTourGuideLoading) {
            return const Center(child: CircularProgressIndicator());
          } else if (state is FetchAllTourGuideError) {
            return Center(child: Text(state.errMessage));
          } else if (state is FetchAllTourGuideLoaded) {
            final guideModel = state.tourGuides;

            return TourGuideBody(guideModel: guideModel);
          } else {
            return const SizedBox();
          }
        },
      ),
    );
  }
}
