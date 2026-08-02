import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/features/stores/presentation/manager/stores_details_cubit/stores_details_cubit.dart';
import 'package:hawdaj/features/stores/presentation/view/widgets/stores_details_items_body.dart';

class StoresDetailsItems extends StatelessWidget {
  const StoresDetailsItems({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocBuilder<StoresDetailsCubit, StoresDetailsState>(
        builder: (context, state) {
          if (state is StoresDetailsLoading) {
            return const Center(child: CircularProgressIndicator());
          } else if (state is StoresDetailsError) {
            return Center(child: Text(state.errMessage));
          } else if (state is StoresDetailsSuccess) {
            final stores = state.storesDetails;

            return StoresDetailsItemsBody(store: stores);
          } else {
            return const SizedBox();
          }
        },
      ),
    );
  }
}
