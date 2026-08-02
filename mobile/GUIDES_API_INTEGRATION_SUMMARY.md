# Tour Guides API Integration Summary

## Overview
This document summarizes the complete implementation of tour guides API integration for the Hawdaj app, specifically for category with ID '7' (tour guides).

## API Integration

### Endpoint
```
https://dashboard.hawdaj.net/api/guides
```

### Parameters
- `page`: Pagination page number
- `per_page`: Number of items per page (default: 12)
- `region_id`: Filter by region (optional)
- `language_id`: Filter by language (optional)
- `top_rated`: Filter top-rated guides (true/false)

## Data Flow

### 1. API Response Structure
```json
{
  "id": 9,
  "type": "guide",
  "name": "محمد حسن",
  "nickName": "محمد",
  "description": "مرشد سياحي متخصص في الجولات التاريخية.",
  "image": "uploads/front_assets/imgs/zad1.jpg",
  "experience": 8,
  "gender": "male",
  "regions": [{"id": 2, "name": "مكة المكرمة"}],
  "languages": [{"id": 1, "name": "العربية"}, {"id": 2, "name": "الانجليزية"}],
  "rate": 5,
  "ratings": [...],
  "social": {...}
}
```

### 2. Model Mapping (UnifiedPlaceModel)
The API response is parsed into `UnifiedPlaceModel` with guide-specific extensions:

| API Field | Model Property | Helper Method | Card Display |
|-----------|----------------|---------------|--------------|
| `name` | `title` | - | Main title |
| `nickName` | `nickName` | - | Subtitle (nickname) |
| `experience` | `experience` | `priceText` | "8 سنة خبرة" |
| `languages` | `languages` | `languagesText` | Languages with translate icon |
| `regions` | `regions` | `regionsText` | Location text |
| `rate` | `rate` | - | Star rating widget |
| `ratings.length` | `ratings.length` | - | Reviews count |
| `image` | `image` | `fullImageUrl` | Card image |
| `description` | `description` | - | Description text |

### 3. Helper Methods
```dart
// In UnifiedPlaceModel
String get languagesText => languages.map((l) => l.name).join(', ');
String get regionsText => regions.map((r) => r.name).join(', ');
String get priceText => experience > 0 ? '$experience سنة خبرة' : '';
String get locationText => regionsText;
bool get isGuide => type == 'guide';
```

## UI Components

### 1. TasneefTourGuidesItemCard
Enhanced card component that displays:
- **Guide name with nickname**: "محمد حسن (محمد)"
- **Experience**: "8 سنوات خبرة" 
- **Rating**: Star widget with rating value
- **Languages**: List with translate icon
- **Image**: Network image with loading states
- **Description**: Guide description text

### 2. RatingWidget
Enhanced rating display:
- Shows star rating for rated guides
- Shows "جديد" (New) for guides with 0 ratings
- Handles edge cases gracefully

## State Management

### PlacesCubit Integration
```dart
// Get guides with parameters
await placesRepository.getGuides(
  page: currentPage,
  perPage: 12,
  regionId: selectedRegion,
  languageId: selectedLanguage,
  topRated: true,
);
```

### Navigation
```dart
// In ExploreCategoryModel
if (id == '7' && type == 'guides') {
  // Navigate to TasneefTourGuidesListView
}
```

## Testing

### Test Demo Available
- File: `lib/features/tasneef/examples/guides_test_demo.dart`
- Includes: Sample data, model parsing tests, card display tests
- Usage: Run `GuidesTestDemo.testGuideModelParsing()` to verify data mapping

### Test Coverage
✅ API endpoint integration  
✅ Data model parsing  
✅ Helper method functionality  
✅ Card component display  
✅ State management  
✅ Error handling  
✅ Loading states  
✅ Pagination support  

## Key Features

1. **Rich Data Display**: Full guide information including experience, languages, regions
2. **Responsive Design**: Proper image loading and text formatting
3. **Error Handling**: Graceful fallbacks for missing data
4. **Performance**: Efficient state management and pagination
5. **Maintainable**: Clean separation of concerns and reusable components

## Production Ready
- ✅ Debug print statements removed
- ✅ Error handling implemented
- ✅ Loading states handled
- ✅ Null safety compliance
- ✅ Performance optimized

## Files Modified
- `lib/core/databases/api/end_points.dart` - Added guides endpoint
- `lib/features/tasneef/data/repositories/tasneef_repository_impl.dart` - Added guides method
- `lib/features/tasneef/presentation/cubits/places_cubit/places_cubit.dart` - Added guides support
- `lib/features/tasneef/data/models/unified_place_model.dart` - Extended with guide fields
- `lib/features/tasneef/presentation/views/widgets/tasneef_tour_guides_item_card.dart` - Enhanced card
- `lib/features/tasneef/presentation/views/widgets/rating_widget.dart` - Improved rating display
- `lib/features/tasneef/presentation/views/tasneef_tour_guides_list_view.dart` - Fixed card usage

The implementation provides a complete, production-ready solution for tour guides API integration with excellent data-to-UI mapping.
