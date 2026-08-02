import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/empty_state_widget.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/landmarks/data/models/list_landmark_response.dart';
import 'package:hawdaj/features/landmarks/presentation/manager/get_my_landmarks_cubit/get_my_landmarks_cubit.dart';
import 'package:hawdaj/features/landmarks/presentation/view/widgets/land_mark_items.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';

class MyLandMarkView extends StatelessWidget {
  const MyLandMarkView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        children: [
          TripStartAppBar(title: 'landmarks'.tr(), showimage: false),
          BlocBuilder<GetMyLandmarksCubit, GetMyLandmarksState>(
            builder: (context, state) {
              if (state is GetMyLandmarksLoaded) {
                return Expanded(
                  child: PagedListView<int, LandmarkItem>(
                    pagingController: state.pagingController,
                    padding: EdgeInsets.symmetric(horizontal: 16.w),
                    builderDelegate: PagedChildBuilderDelegate<LandmarkItem>(
                      itemBuilder: (context, item, index) {
                        return LandMarkItems(landmarkItem: item);
                      },
                      noItemsFoundIndicatorBuilder: (_) => EmptyStateWidget(
                        iconPath: AppAssets.notfoundlandmark,
                        message: 'no_landmarks_title'.tr(),
                        subMessage: "no_landmarks_subtitle".tr(),
                      ),
                      firstPageErrorIndicatorBuilder: (_) => const Center(
                        child: Text('Failed to load landmarks.'),
                      ),
                    ),
                  ),
                );
              } else if (state is GetMyLandmarksError) {
                return Center(child: Text(state.message));
              } else {
                return const Center(child: CircularProgressIndicator());
              }
            },
          ),
          Padding(
            padding: EdgeInsets.all(16.w),
            child: PrimaryButton(
              // padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 16.h),
              title: 'إضافة معلم جديدة',
              onTap: () {
                // showLandmarkTypeBottomSheet(context: context).then((value) {
                //   if (value != null) {
                //     GoRouter.of(
                //       context,
                //     ).push(RoutesKeys.kAddLandmarkView, extra: value);
                //   }
                // });
                //   push(RoutesKeys.kAddPlacesView, context);
              },
            ),
          ),
        ],
      ),
    );
  }
}
