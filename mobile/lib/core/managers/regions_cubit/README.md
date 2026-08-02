# Global Regions Cubit

## Overview
The `RegionsCubit` is a global cubit that manages the regions data throughout the app. It loads regions from the API (`https://dashboard.hawdaj.net/api/regions`) in the background when the app starts.

## Setup
The cubit is already registered in the service locator and added to the main app providers, so it will automatically load data when the app starts.

## Files Created
- `lib/core/repositories/regions_repository.dart` - Abstract repository interface
- `lib/core/repositories/regions_repository_impl.dart` - Repository implementation
- `lib/core/managers/regions_cubit/regions_cubit.dart` - Main cubit file
- `lib/core/managers/regions_cubit/regions_state.dart` - Cubit states
- `lib/core/managers/regions_cubit/regions_example.dart` - Usage examples

## API Response Structure
```json
{
    "code": 200,
    "message": "list of regions",
    "data": [
        {
            "id": 1,
            "name": "الرياض"
        },
        {
            "id": 2,
            "name": "مكة المكرمة"
        }
        // ... more regions
    ]
}
```

## States
- `RegionsInitial` - Initial state
- `RegionsLoading` - Loading regions data
- `RegionsSuccess` - Successfully loaded regions with data
- `RegionsError` - Error loading regions with error message

## Usage Examples

### 1. Basic BlocBuilder Usage
```dart
BlocBuilder<RegionsCubit, RegionsState>(
  builder: (context, state) {
    if (state is RegionsLoading) {
      return CircularProgressIndicator();
    }
    
    if (state is RegionsError) {
      return Text('${"error_label".tr()}: ${state.errMessage}');
    }
    
    if (state is RegionsSuccess) {
      return ListView.builder(
        itemCount: state.regions.length,
        itemBuilder: (context, index) {
          final region = state.regions[index];
          return ListTile(
            title: Text(region.name),
            onTap: () {
              // Handle region selection
            },
          );
        },
      );
    }
    
    return Text('لا توجد بيانات');
  },
)
```

### 2. Direct Access (Helper Methods)
```dart
// Get all regions
List<RegionModel> regions = context.read<RegionsCubit>().regions;

// Get specific region by ID
RegionModel? region = context.read<RegionsCubit>().getRegionById(1);

// Check if data is loaded
bool isLoaded = context.read<RegionsCubit>().isLoaded;

// Refresh data
context.read<RegionsCubit>().fetchRegions();
```

### 3. Dropdown Usage
```dart
RegionsDropdown(
  selectedRegion: selectedRegion,
  onChanged: (RegionModel? region) {
    setState(() {
      selectedRegion = region;
    });
  },
)
```

### 4. Using Helper Class
```dart
// Static helper methods
List<RegionModel> allRegions = RegionsHelper.getAllRegions(context);
RegionModel? specificRegion = RegionsHelper.getRegionById(context, 1);
bool areLoaded = RegionsHelper.areRegionsLoaded(context);
RegionsHelper.refreshRegions(context);
```

## API Headers Required
The following headers are required for the API call (automatically handled):
- `locale: ar`
- `accept-tokenapi: k*RQ=z*2K4@WnA6d2h_&z39?bE9kDszkwh4XTePpU_vA`

## Background Loading
The cubit automatically loads regions data when the app starts. You can check the loading state and handle it appropriately in your UI.

## Error Handling
The cubit handles network errors and API errors gracefully. Use the `RegionsError` state to display appropriate error messages to users.

## Performance Notes
- Data is loaded once on app start and cached in memory
- Use `context.read<RegionsCubit>()` for one-time access
- Use `BlocBuilder` or `BlocListener` for reactive UI updates
- The cubit provides helper methods for common operations
