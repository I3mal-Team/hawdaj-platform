import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/managers/cities_cubit/cities_cubit.dart';
import 'package:hawdaj/core/repositories/cities_repository.dart';
import 'package:hawdaj/core/services/service_locator.dart';
import 'package:hawdaj/features/tasneef/data/models/filter_model.dart';
import 'package:hawdaj/core/components/spaces.dart';

/// إعداد نموذج الفلاتر
List<FilterModel> _filterModel(
  TextEditingController nameController,
  Function(DateTimeRange?) onDateRangeSelected,
  DateTimeRange? range,
  Function(bool?) onOnlineChanged,
  bool? isOnline,
  Function(bool?) onAddressTypeChanged,
  bool? addressTypeValue,
) => [
  FilterModel.eventName(nameController),
  FilterModel.dateRange(onDateRangeSelected, range),
  // FilterModel.isOnline(onOnlineChanged, isOnline),
  FilterModel.addressType(onAddressTypeChanged, addressTypeValue),
];

class EventFilteringWidget extends StatefulWidget {
  const EventFilteringWidget({super.key});

  @override
  State<EventFilteringWidget> createState() => _EventFilteringWidgetState();
}

class _EventFilteringWidgetState extends State<EventFilteringWidget> {
  final TextEditingController nameController = TextEditingController();

  late final CitiesCubit citiesCubit;
  bool? isOnline;
  bool? addressTypeValue;
  DateTimeRange? dateRange;

  @override
  void initState() {
    super.initState();
    citiesCubit = CitiesCubit(citiesRepository: getIt<CitiesRepository>());
  }

  @override
  void dispose() {
    citiesCubit.close();
    nameController.dispose();
    super.dispose();
  }

  List<FilterModel> _buildFilterModels() {
    return _filterModel(
      nameController,
      (range) {
        setState(() {
          dateRange = range;
        });
      },
      dateRange,
      (value) {
        setState(() {
          isOnline = value;
        });
      },
      isOnline,
      (value) {
        setState(() {
          addressTypeValue = value;
        });
      },
      addressTypeValue,
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
                print('addressTypeValue = $addressTypeValue');

                var filters = _buildFilterModels();
                Map<String, dynamic> filterParams = {};

                // جمع القيم بناءً على المفاتيح
                for (var filter in filters) {
                  switch (filter.key) {
                    case 'search':
                      if (nameController.text.isNotEmpty) {
                        filterParams[filter.key] = nameController.text;
                      }
                      break;

                    case 'daterange':
                      if (dateRange != null) {
                        filterParams[filter.key] = dateRange!;
                      }
                      break;

                    case 'is_online':
                      if (isOnline != null) {
                        filterParams[filter.key] = isOnline!;
                      }
                      break;

                    case 'address_type':
                      if (addressTypeValue != null) {
                        // ✅ تحويل القيمة المنطقية إلى نص مناسب للـ API
                        filterParams[filter.key] = addressTypeValue!
                            ? 'map'
                            : 'link';
                        //map
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
