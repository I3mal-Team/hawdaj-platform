import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import 'package:hawdaj/features/stories/presentation/view/widgets/stories_details_items_body.dart';

import '../manager/stories_details/stories_details_cubit.dart';

class StoriesDetailsView extends StatelessWidget {
  const StoriesDetailsView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocBuilder<StoriesDetailsCubit, StoriesDetailsState>(
        builder: (context, state) {
          if (state is StoriesDetailsLoading) {
            return const Center(child: CircularProgressIndicator());
          } else if (state is StoriesDetailsError) {
            return Center(child: Text(state.errMessage));
          } else if (state is StoriesDetailsSuccess) {
            final Stories = state.storiesDetails;

            return StoriesDetailsItemsBody(stories: Stories);
          } else {
            return const SizedBox();
          }
        },
      ),
    );
  }
}
