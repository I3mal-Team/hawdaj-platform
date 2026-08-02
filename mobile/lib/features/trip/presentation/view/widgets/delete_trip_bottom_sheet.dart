import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/features/trip/presentation/manager/delete_trip_cubit/delete_trip_cubit.dart';

import 'package:hawdaj/features/trip/presentation/view/widgets/bottom_sheet_trip_dealt.dart';

class DeleteTripBottomSheet extends StatelessWidget {
  final String token;

  const DeleteTripBottomSheet({super.key, required this.token});

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<DeleteTripCubit, DeleteTripState>(
      listener: (context, state) {
        if (state is DeleteTripSuccess) {
          Navigator.of(context).pop(true);
          showCustomSuccessToast(state.message);
        } else if (state is DeleteTripError) {
          showCustomFailureToast(state.message);
        }
      },
      builder: (context, state) {
        return BottomSheetTripDealt(
          confirm: 'confirm'.tr(),
          cancel: 'cancel'.tr(),
          title: "delete_trip_confirm".tr(),
          message: "delete_trip_warning".tr(),
          onTapConfirm: () {
            context.read<DeleteTripCubit>().deleteTrip(token);
          },
          onTapCancel: () {
            Navigator.pop(context);
          },
        );
      },
    );
  }
}
