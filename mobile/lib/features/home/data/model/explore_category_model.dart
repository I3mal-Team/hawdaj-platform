import 'package:hawdaj/core/routing/routes_keys.dart';
import 'package:hawdaj/core/utils/app_assets.dart';

class ExploreCategoryModel {
  final String id;
  final String name;
  final String imagePath;
  final String routes;
  final String type; // 'places', 'stores', 'zads'
  final bool manor; // Only true for stores

  ExploreCategoryModel({
    required this.id,
    required this.name,
    required this.imagePath,
    required this.routes,
    required this.type,
    this.manor = false,
  });
}

final List<ExploreCategoryModel> dummyCategories = [
  ExploreCategoryModel(
    id: '1',
    name: 'filter_places',
    imagePath: AppAssets.places,
    routes: RoutesKeys.kTasneefPlacesListView,
    type: 'places',
    manor: false,
  ),
  ExploreCategoryModel(
    id: '2',
    name: 'filter_stores',
    imagePath: AppAssets.estate,
    routes: RoutesKeys.kTasneefStoresListView,
    type: 'stores',
    manor: true,
  ),
  ExploreCategoryModel(
    id: '3',
    name: 'filter_zads',

    routes: RoutesKeys.kTasneefZadsListView,
    type: 'zads',
    manor: false,
    imagePath: AppAssets.increasedPng,
  ),
  ExploreCategoryModel(
    id: '4',
    name: 'stories',
    imagePath: AppAssets.sideburn,
    routes: RoutesKeys.kTasneefStoriesListView,
    type: 'stories',
    manor: false,
  ),
  ExploreCategoryModel(
    id: '5',
    name: 'filter_events',
    imagePath: AppAssets.eventsTap,
    routes: RoutesKeys.kTasneefPlacesListView,
    type: 'events',
    manor: true,
  ),
  ExploreCategoryModel(
    id: '6',
    name: 'apps',
    imagePath: AppAssets.applicationTap,
    routes: RoutesKeys.kTasneefAppsListView,
    type: 'apps',
    manor: false,
  ),
  ExploreCategoryModel(
    id: '7',
    name: 'tour_guides',
    imagePath: AppAssets.tour,
    routes: RoutesKeys.kTasneefTourGuidesListView,
    type: 'guides',
    manor: false,
  ),
];

final List<ExploreCategoryModel> dummyLandMarks = [
  ExploreCategoryModel(
    id: '1',
    name: 'filter_places',
    imagePath: AppAssets.places,
    routes: RoutesKeys.kTasneefPlacesListView,
    type: 'place',
    manor: false,
  ),
  ExploreCategoryModel(
    id: '2',
    name: 'filter_stores',
    imagePath: AppAssets.estate,
    routes: RoutesKeys.kTasneefStoresListView,
    type: 'store',
    manor: true,
  ),
  ExploreCategoryModel(
    id: '3',
    name: 'filter_zads',

    routes: RoutesKeys.kTasneefZadsListView,
    type: 'zad',
    manor: false,
    imagePath: AppAssets.increasedPng,
  ),
  ExploreCategoryModel(
    id: '4',
    name: 'stories',
    imagePath: AppAssets.sideburn,
    routes: RoutesKeys.kTasneefStoriesListView,
    type: 'swalef',
    manor: false,
  ),
  ExploreCategoryModel(
    id: '5',
    name: 'filter_events',
    imagePath: AppAssets.eventsTap,
    routes: RoutesKeys.kTasneefPlacesListView,
    type: 'event',
    manor: true,
  ),
  // ExploreCategoryModel(
  //   id: '6',
  //   name: 'apps',
  //   imagePath: AppAssets.applicationTap,
  //   routes: RoutesKeys.kTasneefAppsListView,
  //   type: 'app',
  //   manor: false,
  // ),
  // ExploreCategoryModel(
  //   id: '6',
  //   name: 'tour_guides',
  //   imagePath: AppAssets.tour,
  //   routes: RoutesKeys.kTasneefTourGuidesListView,
  //   type: 'guide',
  //   manor: false,
  // ),
];
