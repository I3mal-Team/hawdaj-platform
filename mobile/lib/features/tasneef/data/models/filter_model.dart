import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/core/managers/cities_cubit/cities_cubit.dart';
import 'package:hawdaj/features/tasneef/data/models/category_model.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/city_filter_widget.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/date_range_picker.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/region_filter_widget.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/store_type_filter.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/zad_food_category.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';

class FilterModel {
  final String name;
  final String key;
  final Widget widget;
  final bool isAddressType;
  FilterModel({
    required this.key,
    required this.widget,
    required this.name,
    this.isAddressType = false,
  });

  static FilterModel placeName(TextEditingController controller) => FilterModel(
    key: 'search',

    widget: CustomTextField(hint: "place_name".tr(), controller: controller),
    name: "place_name".tr(),
  );
  static FilterModel storeName(TextEditingController controller) => FilterModel(
    key: 'search',

    widget: CustomTextField(hint: 'store_name'.tr(), controller: controller),
    name: 'store_name'.tr(),
  );
  static FilterModel zadName(TextEditingController controller) => FilterModel(
    key: 'search',

    widget: CustomTextField(hint: "zad_name".tr(), controller: controller),
    name: "zad_name".tr(),
  );
  static FilterModel sawalefName(TextEditingController controller) =>
      FilterModel(
        key: 'search',

        widget: CustomTextField(
          hint: "sawalef_name".tr(),
          controller: controller,
        ),
        name: 'sawalef_name'.tr(),
      );
  static FilterModel eventName(TextEditingController controller) => FilterModel(
    key: 'search',

    widget: CustomTextField(hint: 'event_name'.tr(), controller: controller),
    name: "event_name".tr(),
  );

  static FilterModel region(
    Function(RegionModel region) onRegionSelected,
    TextEditingController regionController,
  ) => FilterModel(
    key: 'region_id',

    widget: RegionFilterWidget(
      onRegionSelected: onRegionSelected,
      controller: regionController,
    ),
    name: 'region'.tr(),
  );

  static FilterModel city(
    Function(CityModel city) onCitySelected,
    RegionModel? activeRegion,
    TextEditingController cityController,
  ) => FilterModel(
    key: 'city_id',

    widget: activeRegion == null
        ? SizedBox()
        : CityFilterWidget(
            onCitySelected: onCitySelected,
            selectedRegion: activeRegion,
            cityController: cityController,
          ),
    name: 'city'.tr(),
  );

  static FilterModel cityWithCubit(
    Function(CityModel city) onCitySelected,
    RegionModel? activeRegion,
    TextEditingController cityController,
    CitiesCubit citiesCubit,
  ) => FilterModel(
    key: 'city_id',

    widget: activeRegion == null
        ? SizedBox()
        : CityFilterWidgetWithCubit(
            onCitySelected: onCitySelected,
            selectedRegion: activeRegion,
            cityController: cityController,
            citiesCubit: citiesCubit,
          ),
    name: 'city'.tr(),
  );

  static FilterModel isOnline(
    Function(bool? value) onChanged,
    bool? isOnline,
  ) => FilterModel(
    key: 'is_online',
    widget: StoreTypeFilter(onChanged: onChanged, isOnline: isOnline),
    name: 'type'.tr(),
  );

  static FilterModel foodCategory(
    final CategoryModel? selectedCategory,

    final Function(CategoryModel?) onChanged,
  ) => FilterModel(
    key: 'food_categories',

    widget: ZadFoodCategory(
      selectedCategory: selectedCategory,
      onChanged: onChanged,
    ),
    name: 'type'.tr(),
  );
  static FilterModel dateRange(
    final Function(DateTimeRange?) onDateRangeSelected,
    final DateTimeRange? range,
  ) => FilterModel(
    key: 'daterange',

    widget: DateRangePicker(
      onDateRangeSelected: onDateRangeSelected,
      range: range,
    ),
    name: "choose_date".tr(),
  );
  static FilterModel addressType(
    Function(bool? value) onChanged,
    bool? isOnline,
  ) => FilterModel(
    key: 'address_type',
    widget: StoreTypeFilter(
      onChanged: onChanged,
      isOnline: isOnline,
      isAddressType: true,
    ),
    name: 'address_type'.tr(),
  );
}
