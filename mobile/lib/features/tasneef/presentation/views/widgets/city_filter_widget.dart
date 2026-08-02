import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/managers/cities_cubit/cities_cubit.dart';
import 'package:hawdaj/core/repositories/cities_repository.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/features/tasneef/presentation/views/widgets/generic_selection_bottomsheet.dart';
import 'package:hawdaj/core/components/custom_text_field/custom_app_form_text_field.dart';

class CityFilterWidget extends StatelessWidget {
  final Function(CityModel city) onCitySelected;
  final RegionModel selectedRegion;
  final TextEditingController cityController;

  const CityFilterWidget({
    super.key,
    required this.onCitySelected,
    required this.selectedRegion,
    required this.cityController,
  });

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) =>
          CitiesCubit(citiesRepository: getIt<CitiesRepository>())
            ..fetchCitiesByRegion(selectedRegion.id),
      child: _Body(
        onCitySelected: onCitySelected,
        cityController: cityController,
      ),
    );
  }
}

class CityFilterWidgetWithCubit extends StatelessWidget {
  final Function(CityModel city) onCitySelected;
  final RegionModel selectedRegion;
  final TextEditingController cityController;
  final CitiesCubit citiesCubit;

  const CityFilterWidgetWithCubit({
    super.key,
    required this.onCitySelected,
    required this.selectedRegion,
    required this.cityController,
    required this.citiesCubit,
  });

  @override
  Widget build(BuildContext context) {
    return _Body(
      onCitySelected: onCitySelected,
      cityController: cityController,
    );
  }
}

class _Body extends StatelessWidget {
  final TextEditingController cityController;
  const _Body({required this.onCitySelected, required this.cityController});

  final Function(CityModel city) onCitySelected;

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<CitiesCubit, CitiesState>(
      builder: (context, state) {
        if (state is CitiesSuccess) {
          return GestureDetector(
            onTap: () {
              GenericSelectionBottomModal.showInlineSelection<CityModel>(
                context: context,
                items: state.cities,
                title: 'المدينة',
                displayText: (city) => city.name,
                onItemSelected: (city) {
                  onCitySelected(city);
                },
              );
            },
            child: CustomTextField(
              hint: 'المدينة',
              enabled: false,
              controller: cityController,
            ),
          );
        } else if (state is CitiesLoading) {
          return const Center(child: CircularProgressIndicator());
        } else {
          return const Center(child: Text('خطأ في تحميل المدن'));
        }
      },
    );
  }
}
