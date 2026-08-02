import 'package:hawdaj/features/exploration/data/model/glodal_search/item.dart';
import 'package:hawdaj/features/profile/data/model/favorite_model.dart';
import 'package:hawdaj/features/profile/data/model/saved_item_model.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';

UnifiedPlaceModel mapItemGlodalToUnifiedPlace(ItemGlodal item) {
  return UnifiedPlaceModel(
    id: item.id ?? 0,
    slug: item.slug ?? '',
    title: item.title ?? '',
    rate: item.rate?.toDouble() ?? 0.0,

    type: item.type ?? '',
    isFavorite: item.isFavorite ?? false,

    image: item.image ?? '',
    lat: item.lat ?? 0.0,
    long: item.long ?? 0.0,
    galleries: [],
    categories: [],
    active: null,
    viewsNum: 0,
    featured: item.featured,
    visited: null,
    review: 0,
    description: '',
    ratings: [],
    isSaved: null,
    addressType: '',
    city: CityModel(id: item.city?.id ?? 0, name: item.city?.name ?? ''),
    region: RegionModel(
      id: item.region?.id ?? 0,
      name: item.region?.name ?? '',
    ),
  );
}

UnifiedPlaceModel mapItemGlodalToUnifiedFavorite(FavoriteModel item) {
  return UnifiedPlaceModel(
    id: item.id ?? 0,
    slug: item.favoritableSlug ?? '',
    title: item.favoritableTitle ?? '',
    rate: item.favoritableRatings?.toDouble() ?? 0.0,

    type: item.favoritableType ?? '',
    isFavorite: true,

    image: item.favoritableImage ?? '',
    lat: item.favoritableLat ?? 0.0,
    long: item.favoritableLong ?? 0.0,
    galleries: [],
    categories: [],
    active: null,
    viewsNum: 0,
    featured: item.favoritableFeatured,
    visited: null,
    review: 0,
    description: '',
    ratings: [],
    isSaved: null,
    addressType: '',
    city: CityModel(
      id: item.favoritableCity?.id ?? 0,
      name: item.favoritableCity?.name ?? '',
    ),
    region: RegionModel(
      id: item.favoritableRegion?.id ?? 0,
      name: item.favoritableRegion?.name ?? '',
    ),
  );
}

UnifiedPlaceModel mapItemGlodalToUnifiedSaved(SavedItemModel item) {
  return UnifiedPlaceModel(
    id: item.id ?? 0,
    slug: item.savedSlug ?? '',
    title: item.savedTitle ?? '',
    rate: item.savedRatings?.toDouble() ?? 0.0,

    type: item.savedType ?? '',
    isFavorite: false,

    image: item.savedImage ?? '',
    lat: item.savedLat ?? 0.0,
    long: item.savedLong ?? 0.0,
    galleries: [],
    categories: [],
    active: null,
    viewsNum: 0,
    featured: item.savedFeatured,
    visited: null,
    review: 0,
    description: '',
    ratings: [],
    isSaved: null,
    addressType: '',
    city: CityModel(
      id: item.savedCity?.id ?? 0,
      name: item.savedCity?.name ?? '',
    ),
    region: RegionModel(
      id: item.savedRegion?.id ?? 0,
      name: item.savedRegion?.name ?? '',
    ),
  );
}
