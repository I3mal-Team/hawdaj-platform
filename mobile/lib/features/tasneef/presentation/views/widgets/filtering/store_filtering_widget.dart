import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/widgets.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/managers/cities_cubit/cities_cubit.dart';
import 'package:hawdaj/core/repositories/cities_repository.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';
import 'package:hawdaj/features/tasneef/data/models/filter_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/core/components/spaces.dart';

List<FilterModel> _filterModel(
  TextEditingController nameController,
  TextEditingController regionController,
  TextEditingController cityController,
  Function(RegionModel region) onRegionSelected,
  Function(CityModel city) onCitySelected,
  RegionModel? activeRegion,
  CitiesCubit citiesCubit,
  bool? isOnline,
  Function(bool?) onIsOnlineChanged,
) => [
  FilterModel.storeName(nameController),
  FilterModel.region(onRegionSelected, regionController),
  FilterModel.cityWithCubit(
    onCitySelected,
    activeRegion,
    cityController,
    citiesCubit,
  ),
  FilterModel.isOnline(onIsOnlineChanged, isOnline),
];

class StoreFilteringWidget extends StatefulWidget {
  const StoreFilteringWidget({super.key});

  @override
  State<StoreFilteringWidget> createState() => _StoreFilteringWidgetState();
}

class _StoreFilteringWidgetState extends State<StoreFilteringWidget> {
  final TextEditingController nameController = TextEditingController();
  final TextEditingController regionController = TextEditingController();
  final TextEditingController cityController = TextEditingController();

  late final CitiesCubit citiesCubit;
  RegionModel? region;
  CityModel? city;
  bool? isOnline;

  @override
  void initState() {
    super.initState();
    citiesCubit = CitiesCubit(citiesRepository: getIt<CitiesRepository>());
  }

  @override
  void dispose() {
    citiesCubit.close();
    nameController.dispose();
    regionController.dispose();
    cityController.dispose();
    super.dispose();
  }

  List<FilterModel> _buildFilterModels() {
    return _filterModel(
      nameController,
      regionController,
      cityController,
      (region) {
        setState(() {
          city = null;
          this.region = region;
        });
        regionController.text = region.name;
        cityController.clear();
        // Explicitly reload cities for the new region
        citiesCubit.fetchCitiesByRegion(region.id);
      },
      (city) {
        setState(() {
          this.city = city;
        });
        cityController.text = city.name;
      },
      region,
      citiesCubit,
      isOnline,
      (value) {
        setState(() {
          isOnline = value;
        });
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocProvider.value(
      value: citiesCubit,
      child: Container(
        padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 16.h),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            ..._buildFilterModels().map(
              (filter) => Column(children: [filter.widget, HeightSpace(8.h)]),
            ),
            HeightSpace(16.h),
            PrimaryButton(
              title: 'search'.tr(),
              onTap: () {
                var filters = _buildFilterModels();
                Map<String, dynamic> filterParams = {};

                // Collect filter values based on their keys and current state
                for (var filter in filters) {
                  switch (filter.key) {
                    case 'search':
                      if (nameController.text.isNotEmpty) {
                        filterParams[filter.key] = nameController.text;
                      }
                      break;
                    case 'region_id':
                      if (region != null) {
                        filterParams[filter.key] = region!.id;
                      }
                      break;
                    case 'city_id':
                      if (city != null) {
                        filterParams[filter.key] = city!.id;
                      }
                      break;
                    case 'is_online':
                      if (isOnline != null) {
                        filterParams[filter.key] = isOnline!;
                      }
                      break;
                  }
                }

                GoRouter.of(context).pop(filterParams);
              },
            ),
          ],
        ),
      ),
    );
  }
}
