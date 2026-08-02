import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/features/trip/presentation/manager/new_view_trip_cubit/new_view_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/new_widgets/new_trip_plan_body.dart';
import 'package:hawdaj/features/trip/presentation/view/new_widgets/show_my_trip_view_body.dart';

class ShowMyTrip extends StatelessWidget {
  const ShowMyTrip({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<NewViewTripCubit, NewViewTripState>(
      builder: (context, state) {
        if (state is NewViewTripLoading) {
          return const Scaffold(
            body: Center(child: CircularProgressIndicator()),
          );
        }

        if (state is NewViewTripFailure) {
          return Scaffold(
            body: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Text('حدث خطأ أثناء تحميل الرحلة'),
                  HeightSpace(16.h),
                  PrimaryButton(
                    title: '🔄 محاولة مجددة',
                    onTap: () {
                      context.read<NewViewTripCubit>().newViewTrip();
                    },
                  ),
                ],
              ),
            ),
          );
        }

        if (state is NewViewTripSuccess) {
          final tripData = state.response.data!;
          return Scaffold(body: ShowMyTripViewBody(model: tripData));
        }

        return const SizedBox.shrink();
      },
    );
  }
}
