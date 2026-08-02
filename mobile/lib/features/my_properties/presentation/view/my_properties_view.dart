import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:hawdaj/core/components/empty_state_widget.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/routing/route_utils.dart';
import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/my_properties/data/model/convert_property_item.dart';
import 'package:hawdaj/features/my_properties/presentation/manager/my_properties_cubit/my_properties_cubit.dart';
import 'package:hawdaj/features/my_properties/presentation/view/widgets/status_badge.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tab_item.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/tasneef_places_item_card.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';
import 'package:infinite_scroll_pagination/infinite_scroll_pagination.dart';
import 'package:hawdaj/features/my_properties/data/model/my_properties_response.dart';

import 'package:url_launcher/url_launcher.dart';

class MyPropertiesView extends StatefulWidget {
  const MyPropertiesView({super.key});

  @override
  State<MyPropertiesView> createState() => _MyPropertiesViewState();
}

class _MyPropertiesViewState extends State<MyPropertiesView>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;

  final List<String> tabs = const [
    'filter_all',
    'filter_places',
    'filter_stores',
    'filter_events',
    'filter_zads',
  ];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: tabs.length, vsync: this);

    _tabController.addListener(() {
      if (_tabController.indexIsChanging) return;

      setState(() {}); // ✅ يحدث اللون فورًا
      final cubit = context.read<MyPropertiesCubit>();
      cubit.changeFilter(tabs[_tabController.index]);
    });
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<MyPropertiesCubit>();

    return Scaffold(
      body: Column(
        children: [
          /// 🔹 App Bar
          TripStartAppBar(title: 'my_properties'.tr(), showimage: false),

          /// 🔹 Tabs (Filter)
          Container(
            color: Colors.white,
            height: 50.h,
            child: ListView.separated(
              scrollDirection: Axis.horizontal,
              padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 8.h),
              itemCount: tabs.length,
              separatorBuilder: (_, __) => SizedBox(width: 6.w),
              itemBuilder: (context, index) {
                final t = tabs[index];
                final isActive =
                    _tabController.hashCode != null &&
                    _tabController.index == index;

                return TabItem(
                  title: t.tr(context: context),
                  active: isActive,
                  onTap: () {
                    _tabController.animateTo(index);
                  },
                );
              },
            ),
          ),

          /// 🔹 Body
          Expanded(
            child: BlocBuilder<MyPropertiesCubit, MyPropertiesState>(
              builder: (context, state) {
                return PagedListView<int, PropertyItem>(
                  padding: EdgeInsets.symmetric(
                    horizontal: 16.w,
                    vertical: 8.h,
                  ),
                  pagingController: cubit.pagingController,
                  builderDelegate: PagedChildBuilderDelegate<PropertyItem>(
                    itemBuilder: (context, item, index) {
                      final place = mapPropertyItemToUnifiedPlace(item);

                      return TasneefPlacesItemCard(
                        place: place,
                        goToDetails: () {
                          String route;
                          dynamic extra;

                          switch (item.type) {
                            case 'store':
                              route = RoutesKeys.kStoresDetailsItems;
                              extra = item.slug;
                              break;
                            case 'place':
                              route = RoutesKeys.kPlacesDetailsItems;
                              extra = item.slug;
                              break;
                            case 'restaurant':
                              route = RoutesKeys.kRestaurantsDetailsItems;
                              extra = {'slug': item.slug, 'id': item.id};
                              break;
                            case 'event':
                              route = RoutesKeys.kEventsDetailsView;
                              extra = item.slug;
                              break;
                            case 'story':
                              route = RoutesKeys.kStoriesDetailsView;
                              extra = item.slug;
                              break;
                            case 'zad':
                              route = RoutesKeys.kRestaurantsDetailsItems;
                              extra = {'slug': item.slug, 'id': item.id};
                              break;
                            default:
                              route = RoutesKeys.kPlacesDetailsItems;
                              extra = item.slug;
                          }

                          push(route, context, extra: extra);
                        },
                        isFavorite: false,
                        icon2: StatusBadge(status: item.status),
                        child: GestureDetector(
                          onTap: () async {
                            final lat = item.lat;
                            final long = item.long;

                            final url = Uri.parse(
                              "https://www.google.com/maps/search/?api=1&query=$lat,$long",
                            );

                            if (await canLaunchUrl(url)) {
                              await launchUrl(
                                url,
                                mode: LaunchMode.externalApplication,
                              );
                            } else {
                              // ignore: avoid_print
                              print("❌ ${"google_maps_error".tr()}");
                            }
                          },
                          child: SvgPicture.asset(AppAssets.locationPin),
                        ),
                      );
                    },
                    firstPageProgressIndicatorBuilder: (_) =>
                        const Center(child: CircularProgressIndicator()),
                    newPageProgressIndicatorBuilder: (_) =>
                        const Center(child: CircularProgressIndicator()),
                    noItemsFoundIndicatorBuilder: (_) => EmptyStateWidget(
                      message: 'no_properties_title'.tr(),
                      subMessage: 'no_properties_sub'.tr(),
                      iconPath: AppAssets.flag34,
                    ),
                  ),
                );
              },
            ),
          ),

          /// 🔹 Add Button
          Padding(
            padding: EdgeInsets.all(16.w),
            child: PrimaryButton(
              title: 'add_property'.tr(),
              onTap: () async {
                final result = await push(RoutesKeys.kAddPlacesView, context);

                if (result == true) {
                  // يحدث البيانات بعد الرجوع من صفحة الإضافة
                  cubit.pagingController.refresh();
                }
              },
            ),
          ),
        ],
      ),
    );
  }
}
