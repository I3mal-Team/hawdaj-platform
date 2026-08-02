import 'package:hawdaj/features/my_properties/data/model/my_properties_response.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_place_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/features/tasneef/data/models/city_model.dart';

UnifiedPlaceModel mapPropertyItemToUnifiedPlace(PropertyItem item) {
  return UnifiedPlaceModel(
    id: item.id,
    slug: item.slug,
    title: item.title ?? '',
    description: item.description ?? '',
    rate:
        item.rate ??
        0, // مفيش تقييم في PropertyItem (تقدر تضيفه لو السيرفر يرسله)
    type: item.type,
    isFavorite: false, // دايماً false هنا لأن الموديل مش بيحتوي على flag
    image: item.image ?? '',
    lat: item.lat ?? 0.0,
    long: item.long ?? 0.0,
    featured: item.featured ?? false,
    galleries: [], // ممكن تضيفها من API لاحقًا
    categories: [],
    active: item.active,
    viewsNum: item.viewsNum ?? 0,
    visited: null,
    review: 0,
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
