import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/core/styles/app_colors.dart';
import 'package:hawdaj/core/styles/app_text_styles.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/trip/data/model/my_trip_model.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';
import 'package:hawdaj/features/trip/presentation/manager/delete_trip_cubit/delete_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/my_trip_cubit/my_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/new_my_trip_cubit/new_my_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/delete_trip_bottom_sheet.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_date_row.dart';

class MyTripViewBodyItemsList extends StatelessWidget {
  const MyTripViewBodyItemsList({super.key, required this.item});
  final MyTripModel item;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () async {
        final result = await push(
          RoutesKeys.kNewShowMyTrip,
          context,
          extra: item.token,
        );
        if (result == true) {
          context.read<NewMyTripCubit>().refresh();
          // context.read<MyTripCubit>().refresh(); // أو refresh()
        }
      },
      child: Container(
        width: 361.w,
        margin: EdgeInsets.only(bottom: 12.h),
        padding: EdgeInsets.all(12.r),
        clipBehavior: Clip.antiAlias,
        decoration: ShapeDecoration(
          color: const Color(0xFFFCFCFD),
          shape: RoundedRectangleBorder(
            side: BorderSide(width: 1, color: const Color(0xFFF8FAFC)),
            borderRadius: BorderRadius.circular(8),
          ),
        ),
        child: Row(
          children: [
            Container(
              width: 76.w,
              height: 76.h,
              clipBehavior: Clip.antiAlias,
              decoration: ShapeDecoration(
                image: DecorationImage(
                  image: AssetImage(AppAssets.onboarding(2)),
                  fit: BoxFit.cover,
                ),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
            WidthSpace(12.w),

            Expanded(
              child: Stack(
                children: [
                  Align(
                    alignment: Alignment.bottomLeft,
                    child: Image.asset(
                      AppAssets.converted,
                      fit: BoxFit.cover,
                      width: 100.w,
                      height: 90.h,
                    ),
                  ),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Expanded(
                            child: Text(
                              item.name,
                              style: AppTextStyles.font18Bold.copyWith(
                                color: AppColors.obsidianBlack,
                              ),
                            ),
                          ),
                          GestureDetector(
                            onTap: () async {
                              final parentCtx = context;
                              final result = await baseBottomSheet(
                                context: context,
                                hideNavBar: false,
                                child: MultiBlocProvider(
                                  providers: [
                                    BlocProvider(
                                      create: (ctx) =>
                                          DeleteTripCubit(getIt<TripRepo>()),
                                    ),
                                  ],
                                  child: DeleteTripBottomSheet(
                                    token: item.token,
                                  ),
                                ),
                              );

                              if (!parentCtx.mounted) return;
                              if (result == true) {
                                parentCtx.read<NewMyTripCubit>().refresh();
                                // parentCtx.read<MyTripCubit>().refresh();
                              }
                            },
                            child: SvgPicture.asset(AppAssets.trash),
                          ),
                        ],
                      ),
                      Row(
                        children: [
                          Expanded(
                            child: TripDateRow(
                              iconPath: AppAssets.location,
                              label: item.region1Object.name,
                              subtitle: '',
                            ),
                          ),
                          Expanded(
                            child: TripDateRow(
                              iconPath: AppAssets.locationTick,
                              label: item.region2Object.name,
                              subtitle: '',
                            ),
                          ),
                        ],
                      ),
                      HeightSpace(12.h),
                      Row(
                        children: [
                          Expanded(
                            child: TripDateRow(
                              label: item.startDate,
                              subtitle: '',
                            ),
                          ),
                          Expanded(
                            child: TripDateRow(
                              iconPath: AppAssets.calendarSvg,
                              label: item.endDate,
                              subtitle: '',
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
