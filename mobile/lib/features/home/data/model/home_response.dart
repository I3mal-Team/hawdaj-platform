import 'package:hawdaj/features/home/data/model/application_model/application_model.dart';
import 'package:hawdaj/features/home/data/model/global_map_data_model/global_map_data_model.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_response.dart';
import 'package:hawdaj/features/home/data/model/map_data_model/map_data_model.dart';
import 'package:hawdaj/features/home/data/model/places_model/place_response.dart';
import 'package:hawdaj/features/home/data/model/safwests_model/safwests_response.dart';
import 'package:hawdaj/features/home/data/model/service_model/service_model.dart';
import 'package:hawdaj/features/home/data/model/top_events_model/top_events_response.dart';
import 'package:hawdaj/features/home/data/model/zad_model/zad_response.dart';

class HomeResponse {
  final PlaceResponse places;
  final PlaceResponse topVisitedPlaces;
  final TopEventsResponse topEvents;
  final ZadResponse zads;
  final ZadResponse topVisitedStores;
  final List<ApplicationModel> applications;
  final SafwestResponse safwests;
  final GuideResponse guides;
  final List<ServiceModel> services;
  final List<GlobalMapDataModel> globalMapData;
  final List<MapDataModel> mapData;

  HomeResponse({
    required this.places,
    required this.topVisitedPlaces,
    required this.topEvents,
    required this.zads,
    required this.topVisitedStores,
    required this.applications,
    required this.safwests,
    required this.guides,
    required this.services,
    required this.globalMapData,
    required this.mapData,
  });

  factory HomeResponse.fromJson(Map<String, dynamic> json) {
    List<T> parseList<T>(
      dynamic data,
      T Function(Map<String, dynamic>) fromJson,
    ) {
      if (data is List) {
        return data.map((e) => fromJson(e)).toList();
      } else if (data is Map && data['items'] is List) {
        return (data['items'] as List).map((e) => fromJson(e)).toList();
      }
      return [];
    }

    return HomeResponse(
      places: PlaceResponse.fromJson(json['places'] ?? {}),
      topVisitedPlaces: PlaceResponse.fromJson(json['topVisitedPlaces'] ?? {}),
      topEvents: TopEventsResponse.fromJson(json['topEvents'] ?? {}),
      zads: ZadResponse.fromJson(json['zads'] ?? {}),
      topVisitedStores: ZadResponse.fromJson(json['topVisitedStores'] ?? {}),
      applications: parseList(
        json['applications'] ?? [],
        ApplicationModel.fromJson,
      ),
      safwests: SafwestResponse.fromJson(json['safwests'] ?? {}),
      guides: GuideResponse.fromJson(json['guides'] ?? {}),
      services: parseList(json['services'] ?? [], ServiceModel.fromJson),
      globalMapData: parseList(
        json['globalMapData'] ?? [],
        GlobalMapDataModel.fromJson,
      ),
      mapData: parseList(json['MapData'] ?? [], MapDataModel.fromJson),
    );
  }
}
