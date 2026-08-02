import 'package:hawdaj/features/tasneef/data/models/category_model.dart'
    show CategoryModel;
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';

UnifiedPlaceModel newTripPlaceFromEnhancedPlace(Place place) {
  return UnifiedPlaceModel(
    id: place.id ?? 0,
    slug: place.slug,
    title: place.title ?? '',
    description: place.description ?? '',
    rate: place.rate ?? 0,
    type: place.type ?? '',
    isFavorite: false,
    image: place.image ?? '',
    lat: place.lat ?? 0.0,
    long: place.long ?? 0.0,
    galleries: [],
    categories: place.categories
        .map((catId) => CategoryModel(id: catId))
        .toList(),
    active: null,
    viewsNum: 0,
    featured: place.featured,
    visited: place.visited,
    review: 0,
    ratings: [],
    isSaved: null,
    addressType: place.address ?? '',
    city: CityModel(id: place.city?.id ?? 0, name: place.city?.name ?? ''),
    region: RegionModel(
      id: place.region?.id ?? 0,
      name: place.region?.name ?? '',
    ),
  );
}
