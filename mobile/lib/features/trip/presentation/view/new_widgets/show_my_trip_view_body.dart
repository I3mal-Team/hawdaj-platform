import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/components/bottom_sheet/base_bottom_sheet.dart';
import 'package:hawdaj/core/components/custom_failure_toast.dart';

import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/places/presentation/view/widgets/places_details_items_body.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/data/model/new_trip_place_mapper.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';
import 'package:hawdaj/features/trip/presentation/manager/delete_trip_cubit/delete_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/manager/new_my_trip_cubit/new_my_trip_cubit.dart';
import 'package:hawdaj/features/trip/presentation/view/new_widgets/trip_day_card.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/delete_trip_bottom_sheet.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_details_card.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_program_header.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';
import 'package:share_plus/share_plus.dart';

class ShowMyTripViewBody extends StatefulWidget {
  const ShowMyTripViewBody({super.key, required this.model});
  final EnhancedTripData model;

  @override
  State<ShowMyTripViewBody> createState() => _ShowMyTripViewBodyState();
}

class _ShowMyTripViewBodyState extends State<ShowMyTripViewBody> {
  late EnhancedTripData currentModel;

  @override
  void initState() {
    super.initState();
    currentModel = widget.model;
    print("Enhanced days count: ${currentModel.enhancedData.length}");
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        CustomScrollView(
          slivers: [
            SliverToBoxAdapter(
              child: TripStartAppBar(title: 'trip_program'.tr()),
            ),
            SliverToBoxAdapter(
              child: TripDetailsCard(
                iconOnePath: AppAssets.sendSquare,
                iconTwoPath: AppAssets.trash,

                startDate: formatArabicDate(widget.model.startDate),
                endDate: formatArabicDate(widget.model.endDate),
                region1: widget.model.startRegion?.name.toString() ?? "",
                region2: widget.model.endRegion?.name.toString() ?? "",
                buttonTwo: "delete".tr(),
                buttonOne: "share".tr(),
                onButtonOnePressed: () {
                  final link =
                      'http://hawdaj.net/ar/trips/${widget.model.token}';
                  final title = 'trip_program'.tr();

                  if (link.isNotEmpty) {
                    Share.share(link, subject: title);
                  } else {
                    debugPrint('Cannot share empty text');
                    showCustomFailureToast('no_share_link'.tr());
                  }
                },
                onButtonTwoPressed: () async {
                  final result = await baseBottomSheet(
                    context: context,
                    hideNavBar: false,
                    child: MultiBlocProvider(
                      providers: [
                        BlocProvider(
                          create: (ctx) => DeleteTripCubit(getIt<TripRepo>()),
                        ),
                        BlocProvider.value(
                          value: context.read<NewMyTripCubit>(),
                        ),
                      ],
                      child: DeleteTripBottomSheet(token: widget.model.token),
                    ),
                  );

                  if (result == true) {
                    Navigator.of(context).pop(true);
                  }
                },
              ),
            ),
            SliverToBoxAdapter(
              child: TripProgramHeader(
                onTap: () {
                  final dailyPlaces = currentModel.enhancedData.map((day) {
                    return day.allPlaces.map((place) {
                      return newTripPlaceFromEnhancedPlace(place);
                    }).toList();
                  }).toList();

                  push(RoutesKeys.kMapTripView, context, extra: dailyPlaces);
                },
              ),
            ),
            SliverList(
              delegate: SliverChildBuilderDelegate((context, index) {
                final day = currentModel.enhancedData[index];
                return TripDayCard(
                  model: day,
                  dayIndex: index,
                  showDelete: false,
                );
              }, childCount: currentModel.enhancedData.length),
            ),
          ],
        ),
      ],
    );
  }
}
