import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/tour_guide_details_cubit/tour_guide_details_cubit.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/tour_guide_details_items_body.dart';

class TourGuideDetailsView extends StatelessWidget {
  const TourGuideDetailsView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocBuilder<TourGuideDetailsCubit, TourGuideDetailsState>(
        builder: (context, state) {
          if (state is TourGuideDetailsStateLoading) {
            return const Center(child: CircularProgressIndicator());
          } else if (state is TourGuideDetailsStateError) {
            return Center(child: Text(state.errMessage));
          } else if (state is TourGuideDetailsStateSuccess) {
            final guideModel = state.tourGuide;

            return TourGuideDetailsItemsBody(guideModel: guideModel);
          } else {
            return const SizedBox();
          }
        },
      ),
    );
  }
}
