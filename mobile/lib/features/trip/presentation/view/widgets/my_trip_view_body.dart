import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/features/trip/data/model/my_trip_model.dart';
import 'package:hawdaj/features/trip/presentation/manager/my_trip_cubit/my_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/new_my_trip_cubit/new_my_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/my_trip_view_body_items_list.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';

class MyTripViewBody extends StatelessWidget {
  const MyTripViewBody({super.key});

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<MyTripCubit>();

    return Scaffold(
      body: Column(
        children: [
          TripStartAppBar(title: "my_trips".tr()),
          Expanded(
            child: Padding(
              padding: EdgeInsets.symmetric(horizontal: 16.w),
              child: PagedListView<int, MyTripModel>.separated(
                pagingController: cubit.pagingController,
                scrollDirection: Axis.vertical,
                builderDelegate: PagedChildBuilderDelegate<MyTripModel>(
                  itemBuilder: (context, item, index) {
                    return MyTripViewBodyItemsList(item: item);
                  },
                  firstPageErrorIndicatorBuilder: (context) =>
                      Center(child: Text('error_loading_offers'.tr())),
                  noItemsFoundIndicatorBuilder: (context) => Center(
                    child: Column(
                      mainAxisSize: MainAxisSize.max,
                      mainAxisAlignment: MainAxisAlignment.center,
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        Text('no_trips'.tr()),

                        TextButton(
                          onPressed: () {
                            context.read<MyTripCubit>().refresh();
                          },
                          child: Text('add_new_trip'.tr()),
                        ),
                      ],
                    ),
                  ),
                  newPageProgressIndicatorBuilder: (context) =>
                      const Center(child: CircularProgressIndicator()),
                  firstPageProgressIndicatorBuilder: (context) =>
                      const Center(child: CircularProgressIndicator()),
                ),
                separatorBuilder: (context, index) => SizedBox(width: 12.w),
              ),
            ),
          ),
        ],
      ),
      bottomNavigationBar: Container(
        padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 12.h),

        width: double.infinity,
        height: 80.h,
        child: PrimaryButton(
          title: "add_trip_button".tr(),
          onTap: () {
            push(RoutesKeys.kAddTripView, context);
          },
        ),
      ),
    );
  }
}
