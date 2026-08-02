import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/features/trip/presentation/manager/fetch_prices_cubit/fetch_prices_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/prepare_trip_wizard/prepare_trip_wizard_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/custom_widgets_head_trip.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/price_option_tile.dart';

class CustomTripDetailsPrices extends StatelessWidget {
  const CustomTripDetailsPrices({super.key});

  void _togglePrice(BuildContext context, int id) {
    final cubit = context.read<PrepareTripWizardCubit>();
    final list = List<int>.from(cubit.state.draft.price);
    list.contains(id) ? list.remove(id) : list.add(id);
    cubit.setPrices(list);
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<FetchPricesCubit, FetchPricesState>(
      builder: (context, state) {
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CustomWidgetsHeadTrip(
              title: "trip_details_title".tr(),
              showButton: false,
              subtitleText: "trip_details_subtitle".tr(),
            ),
            HeightSpace(16.h),

            if (state is FetchPricesLoading)
              const Center(child: CircularProgressIndicator()),

            if (state is FetchPricesError)
              Column(
                children: [
                  Text('${"error_label".tr()}: ${state.message}'),
                  HeightSpace(8.h),
                  ElevatedButton(
                    onPressed: () =>
                        context.read<FetchPricesCubit>().fetchPrices(),
                    child: Text("retry".tr()),
                  ),
                ],
              ),

            if (state is FetchPricesSuccess)
              BlocBuilder<PrepareTripWizardCubit, PrepareTripWizardState>(
                builder: (context, wizardState) {
                  final selected = wizardState.draft.price; // قائمة متعددة
                  final prices = state.pricesModel;

                  // تحديد كل العناصر لو القائمة فاضية
                  if (selected.isEmpty) {
                    final allIds = prices
                        .map((e) => e.id ?? -1)
                        .where((id) => id > 0)
                        .toList();
                    context.read<PrepareTripWizardCubit>().setPrices(allIds);
                  }

                  return SizedBox(
                    height: 200.h,
                    child: GridView.builder(
                      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: 2,
                        mainAxisSpacing: 12.h,
                        crossAxisSpacing: 12.w,
                        childAspectRatio: 3.0,
                      ),
                      itemCount: prices.length,
                      itemBuilder: (context, i) {
                        final item = prices[i];
                        final id = item.id ?? -1;
                        if (id <= 0) return const SizedBox.shrink();

                        return PriceOptionTile(
                          isSelected: selected.contains(id),
                          title: item.name ?? '',
                          onTap: () => _togglePrice(context, id),
                        );
                      },
                    ),
                  );
                },
              ),
          ],
        );
      },
    );
  }
}
