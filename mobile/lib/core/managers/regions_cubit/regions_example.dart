import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hawdaj/core/components/custom_success_toast.dart';
import 'package:hawdaj/core/managers/regions_cubit/regions_cubit.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';

/// Example widget showing how to use the global RegionsCubit
/// This cubit is already loaded in the background when the app starts
/// and can be accessed from anywhere in the app
class RegionsExample extends StatelessWidget {
  const RegionsExample({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('المناطق'),
        actions: [
          IconButton(
            onPressed: () {
              // Refresh regions data
              context.read<RegionsCubit>().fetchRegions();
            },
            icon: const Icon(Icons.refresh),
          ),
        ],
      ),
      body: BlocBuilder<RegionsCubit, RegionsState>(
        builder: (context, state) {
          if (state is RegionsLoading) {
            return const Center(child: CircularProgressIndicator());
          }

          if (state is RegionsError) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(
                    'خطأ: ${state.errMessage}',
                    style: const TextStyle(color: Colors.red),
                  ),
                  const SizedBox(height: 16),
                  ElevatedButton(
                    onPressed: () {
                      context.read<RegionsCubit>().fetchRegions();
                    },
                    child: Text("retry".tr()),
                  ),
                ],
              ),
            );
          }

          if (state is RegionsSuccess) {
            return ListView.builder(
              itemCount: state.regions.length,
              itemBuilder: (context, index) {
                final region = state.regions[index];
                return ListTile(
                  leading: CircleAvatar(child: Text('${region.id}')),
                  title: Text(region.name),
                  onTap: () {
                    showCustomSuccessToast('تم اختيار ${region.name}');
                  },
                );
              },
            );
          }

          return Center(child: Text("no_data".tr()));
        },
      ),
    );
  }
}

/// Example of how to access regions data directly without UI
class RegionsHelper {
  /// Get all regions
  static List<RegionModel> getAllRegions(BuildContext context) {
    return context.read<RegionsCubit>().regions;
  }

  /// Get region by ID
  static RegionModel? getRegionById(BuildContext context, int id) {
    return context.read<RegionsCubit>().getRegionById(id);
  }

  /// Check if regions are loaded
  static bool areRegionsLoaded(BuildContext context) {
    return context.read<RegionsCubit>().isLoaded;
  }

  /// Refresh regions data
  static void refreshRegions(BuildContext context) {
    context.read<RegionsCubit>().fetchRegions();
  }
}

/// Example of using regions in a dropdown
class RegionsDropdown extends StatefulWidget {
  final Function(RegionModel?)? onChanged;
  final RegionModel? selectedRegion;

  const RegionsDropdown({super.key, this.onChanged, this.selectedRegion});

  @override
  State<RegionsDropdown> createState() => _RegionsDropdownState();
}

class _RegionsDropdownState extends State<RegionsDropdown> {
  @override
  Widget build(BuildContext context) {
    return BlocBuilder<RegionsCubit, RegionsState>(
      builder: (context, state) {
        if (state is RegionsSuccess) {
          return DropdownButton<RegionModel>(
            value: widget.selectedRegion,
            hint: Text("choose_regions_hint".tr()),
            isExpanded: true,
            items: state.regions.map((region) {
              return DropdownMenuItem<RegionModel>(
                value: region,
                child: Text(region.name),
              );
            }).toList(),
            onChanged: widget.onChanged,
          );
        }

        if (state is RegionsLoading) {
          return const SizedBox(
            height: 48,
            child: Center(child: CircularProgressIndicator()),
          );
        }

        return const SizedBox(
          height: 48,
          child: Center(child: Text('خطأ في تحميل المناطق')),
        );
      },
    );
  }
}
