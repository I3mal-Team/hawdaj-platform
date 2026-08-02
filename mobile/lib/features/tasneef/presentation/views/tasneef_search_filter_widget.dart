import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/spaces.dart';
import 'package:hawdaj/features/tasneef/presentation/cubits/places_cubit/places_cubit.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/filtering/event_filtering_widget.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/filtering/places_fltering_widget.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/filtering/sawalef_filtering_widget.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/filtering/store_filtering_widget.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/filtering/zad_filtering_widget.dart';

class TasneefSearchFilterWidget extends StatefulWidget {
  final String itemType; // 'places', 'stores', 'zads', etc.

  const TasneefSearchFilterWidget({super.key, this.itemType = 'places'});

  @override
  State<TasneefSearchFilterWidget> createState() =>
      _TasneefSearchFilterWidgetState();
}

class _TasneefSearchFilterWidgetState extends State<TasneefSearchFilterWidget> {
  final TextEditingController searchController = TextEditingController();
  bool _hasActiveFilters = false;

  @override
  void dispose() {
    searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,

      children: [
        Expanded(
          child: TextField(
            controller: searchController,
            textAlign: TextAlign.right,
            decoration: InputDecoration(
              hintText: "search_anything".tr(),

              hintStyle: TextStyle(color: Colors.black87, fontSize: 14.sp),
              border: InputBorder.none,
            ),
            onSubmitted: (value) {
              _performSearch(context, value);
            },
          ),
        ),
        WidthSpace(5.w),
        if (containsFilter.contains(widget.itemType))
          IconButton(
            icon: Icon(Icons.filter_list, size: 24.sp),
            onPressed: () {
              _showFilteringModal(context);
            },
          ),
        if (_hasActiveFilters)
          IconButton(
            icon: Icon(Icons.filter_list_off, size: 24.sp, color: Colors.red),
            onPressed: () {
              _clearFilters(context);
            },
          ),
        IconButton(
          icon: Icon(Icons.search, size: 24.sp),
          onPressed: () {
            _performSearch(context, searchController.text);
          },
        ),
      ],
    );
  }

  void _performSearch(BuildContext context, String searchText) {
    if (searchText.trim().isEmpty) return;

    final placesCubit = context.read<PlacesCubit>();
    Map<String, dynamic> filterParams = {'search': searchText.trim()};

    // Update filter state
    setState(() {
      _hasActiveFilters = true;
    });
    placesCubit.search = searchText.trim();
    switch (widget.itemType) {
      case 'places':
        placesCubit.filterPlaces(filterParams: filterParams);
        break;
      case 'stores':
        placesCubit.getItemsByType(type: 'stores', isRefresh: true);
        break;
      case 'zads':
        placesCubit.getItemsByType(type: 'zads', isRefresh: true);
        break;
      case 'events':
        placesCubit.getItemsByType(type: 'events', isRefresh: true);
        break;
      case 'stories':
        placesCubit.getItemsByType(type: 'stories', isRefresh: true);
        break;
      case 'apps':
        placesCubit.getItemsByType(type: 'apps', isRefresh: true);
        break;
      case 'guides':
        placesCubit.getItemsByType(type: 'guides', isRefresh: true);
        break;
      default:
        placesCubit.filterPlaces(filterParams: filterParams);
    }
  }

  var containsFilter = ['places', 'events', 'zads', 'stores', 'stories'];
  Widget get filterWidget {
    if (widget.itemType == 'places') {
      return PlacesFilteringWidget();
    } else if (widget.itemType == 'stores') {
      return StoreFilteringWidget();
    } else if (widget.itemType == 'zads') {
      return ZadFilteringWidget();
    } else if (widget.itemType == 'stories') {
      return SwalefFilteringWidget();
    } else if (widget.itemType == 'events') {
      return EventFilteringWidget();
    }

    return SizedBox();
  }

  void _showFilteringModal(BuildContext context) async {
    var filterParams = await showModalBottomSheet<Map<String, dynamic>>(
      context: context,
      backgroundColor: Colors.white,
      isScrollControlled: true, // 👈 مهم جداً
      builder: (context) {
        return Padding(
          // 👇 علشان الBottomSheet يطلع فوق الكيبورد وما يتغطّاش
          padding: EdgeInsets.only(
            bottom: MediaQuery.of(context).viewInsets.bottom,
          ),
          child: SingleChildScrollView(child: filterWidget),
        );
      },
    );

    if (filterParams == null || filterParams.isEmpty) return;
    if (!mounted) return;

    // Update filter state
    setState(() {
      _hasActiveFilters = true;
    });

    final placesCubit = context.read<PlacesCubit>();
    placesCubit.search = filterParams['search'];
    placesCubit.regionId = filterParams['region_id'];
    placesCubit.cityId = filterParams['city_id'];

    switch (widget.itemType) {
      case 'places':
        // Places filter supports: search, region_id, city_id, lat, lng, address_type
        placesCubit.getItemsByType(
          type: 'places',

          lat: filterParams['lat'],
          lng: filterParams['lng'],
          addressType: filterParams['address_type'],
          isRefresh: true,
        );
        break;
      case 'stores':
        // Store filter supports: search, region_id, city_id, is_online, lat, lng, food_categories
        List<int>? foodCategoryIds;
        if (filterParams['food_categories'] != null) {
          if (filterParams['food_categories'] is List) {
            foodCategoryIds = (filterParams['food_categories'] as List)
                .map((item) => item.id as int)
                .toList();
          } else if (filterParams['food_categories'] is Map) {
            foodCategoryIds = [filterParams['food_categories']['id'] as int];
          }
        }
        placesCubit.getItemsByType(
          type: 'stores',

          isOnline: filterParams['is_online'],
          lat: filterParams['lat'],
          lng: filterParams['lng'],
          foodCategories: foodCategoryIds,
          isRefresh: true,
        );
        break;
      case 'zads':
        // Zad filter supports: search, region_id, city_id, food_categories, lat, lng
        List<int>? zadFoodCategoryIds;
        if (filterParams['food_categories'] != null) {
          if (filterParams['food_categories'] is List) {
            zadFoodCategoryIds = (filterParams['food_categories'] as List)
                .map((item) => item.id as int)
                .toList();
          } else if (filterParams['food_categories'] is Map) {
            zadFoodCategoryIds = [filterParams['food_categories']['id'] as int];
          }
        }
        placesCubit.getItemsByType(
          type: 'zads',

          lat: filterParams['lat'],
          lng: filterParams['lng'],
          foodCategories: zadFoodCategoryIds,
          isRefresh: true,
        );
        break;
      case 'events':
        // Event filter supports: search, daterange, is_online, lat, lng
        String? eventDateRange;
        if (filterParams['daterange'] != null) {
          final DateTimeRange dateRange = filterParams['daterange'];
          final DateFormat formatter = DateFormat('dd/MM/yyyy');
          // ✅ Format as "20/10/2025-23/10/2025"
          eventDateRange =
              '${formatter.format(dateRange.start)}-${formatter.format(dateRange.end)}';
        }
        placesCubit.getItemsByType(
          type: 'events',

          // isOnline: filterParams['is_online'],
          daterange: eventDateRange,
          lat: filterParams['lat'],
          lng: filterParams['lng'],
          addressType: filterParams['address_type'],

          isRefresh: true,
        );
        break;
      case 'stories':
        // Stories filter supports: search, lat, lng
        placesCubit.getItemsByType(
          type: 'stories',

          lat: filterParams['lat'],
          lng: filterParams['lng'],
          isRefresh: true,
        );
        break;
      case 'apps':
        // Apps don't have specific filters, using search only
        placesCubit.getItemsByType(type: 'apps', isRefresh: true);
        break;
      case 'guides':
        // Guides don't have specific filters, using search only
        placesCubit.getItemsByType(type: 'guides', isRefresh: true);
        break;
      default:
        placesCubit.getItemsByType(type: 'places', isRefresh: true);
    }
  }

  void _clearFilters(BuildContext context) {
    // Clear search text and filter state
    searchController.clear();
    setState(() {
      _hasActiveFilters = false;
    });

    final placesCubit = context.read<PlacesCubit>();

    // Reload without any filters
    switch (widget.itemType) {
      case 'places':
        placesCubit.getItemsByType(type: 'places', isRefresh: true);
        break;
      case 'stores':
        placesCubit.getItemsByType(type: 'stores', isRefresh: true);
        break;
      case 'zads':
        placesCubit.getItemsByType(type: 'zads', isRefresh: true);
        break;
      case 'events':
        placesCubit.getItemsByType(type: 'events', isRefresh: true);
        break;
      case 'stories':
        placesCubit.getItemsByType(type: 'stories', isRefresh: true);
        break;
      case 'apps':
        placesCubit.getItemsByType(type: 'apps', isRefresh: true);
        break;
      case 'guides':
        placesCubit.getItemsByType(type: 'guides', isRefresh: true);
        break;
      default:
        placesCubit.getItemsByType(type: 'places', isRefresh: true);
    }
  }
}
