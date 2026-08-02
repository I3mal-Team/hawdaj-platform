import 'package:dartz/dartz.dart';
import 'package:dio/dio.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/paginated_response.dart';
import 'package:hawdaj/features/trip/data/model/enhanced_trip_response.dart';
import 'package:hawdaj/features/trip/data/model/my_trip_model.dart';
import 'package:hawdaj/features/trip/data/model/new/new_my_trips_response.dart';
import 'package:hawdaj/features/trip/data/model/new_trip_prepare_request.dart';
import 'package:hawdaj/features/trip/data/model/prices_model/prices_model.dart';
import 'package:hawdaj/features/trip/data/model/trip_details.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/prepare_trip_data.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/prepare_trip_model.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/save_trip_to_email_params.dart';
import 'package:hawdaj/features/trip/data/model/trip_model/trip_model.dart';
import 'package:hawdaj/features/trip/data/repo/trip_repo.dart';

class TripRepoImp extends TripRepo {
  final ApiConsumer apiConsumer;
  TripRepoImp(this.apiConsumer);
  @override
  Future<Either<Failure, List<PricesModel>>> fetchPrices() {
    return apiConsumer.handleRequest(() => apiConsumer.get(EndPoints.prices), (
      data,
    ) {
      final List<dynamic> pricesList = data['data'];
      return pricesList
          .map((price) => PricesModel.fromJson(price as Map<String, dynamic>))
          .toList();
    });
  }

  @override
  Future<Either<Failure, List<PricesModel>>> fetchCategory() {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.categoriesPlaces),
      (data) {
        final List<dynamic> pricesList = data['data'];
        return pricesList
            .map((price) => PricesModel.fromJson(price as Map<String, dynamic>))
            .toList();
      },
    );
  }

  @override
  Future<Either<Failure, TripModel>> prepareTrip(PrepareTripModel body) {
    return apiConsumer.handleRequest(
      () => apiConsumer.post(EndPoints.prepare, data: body.toJson()),
      (data) => TripModel.fromJson(data['data']),
    );
  }

  @override
  Future<Either<Failure, TripData>> finishTripDetails(String token) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.finishTrip(token)),
      (data) => TripData.fromJson(data['data']),
    );
  }

  @override
  Future<Either<Failure, String>> saveTripToEmail(SaveTripToEmailParams body) {
    return apiConsumer.handleRequest(
      () => apiConsumer.post(EndPoints.saveTripToEmail, data: body.toJson()),
      (data) => data['message'],
    );
  }

  @override
  Future<Either<Failure, String>> saveTrip(SaveTripToEmailParams body) {
    return apiConsumer.handleRequest(
      () => apiConsumer.post(EndPoints.saveTrip, data: body.toJson()),
      (data) => data['message'],
    );
  }

  @override
  Future<Either<Failure, PaginatedResponse<MyTripModel>>> myTrip(int page) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.myTrip(page)),
      (data) => PaginatedResponse<MyTripModel>.fromJson(
        data,
        (json) => MyTripModel.fromJson(json),
      ),
    );
  }

  @override
  Future<Either<Failure, String>> deleteTrip(String token) {
    return apiConsumer.handleRequest(
      () => apiConsumer.delete(EndPoints.deleteTrip(token)),
      (data) => data['message'],
    );
  }

  @override
  Future<Either<Failure, TripDetailsModel>> viewTrip(String token) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.viewTrip(token)),
      (data) => TripDetailsModel.fromJson(data['data']),
    );
  }

  @override
  Future<Either<Failure, EnhancedTripResponse>> newPrepareTrip(
    NewTripPrepareParams body,
  ) {
    return apiConsumer.handleRequest(
      () => apiConsumer.post(EndPoints.prepareV2, data: body.toJson()),
      (data) => EnhancedTripResponse.fromJson(data),
    );
  }

  @override
  Future<Either<Failure, EnhancedTripResponse>> showPrepareTrip(String token) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.showPrepareTrip(token)),
      (data) => EnhancedTripResponse.fromJson(data),
    );
  }

  @override
  Future<Either<Failure, String>> saveTripV2({
    required String token,
    required String name,
    required List<List<int>> items,
  }) {
    final data = {'prepare_token': token, 'name': name, 'items': items};

    return apiConsumer.handleRequest(
      () => apiConsumer.post(EndPoints.saveTripV2, data: data),
      (response) => response['message'],
    );
  }

  @override
  Future<Either<Failure, PaginatedResponse<TripItem>>> newMyTrip(int page) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.newMyTrip(page)),
      (data) => PaginatedResponse<TripItem>.fromJson(
        data,
        (json) => TripItem.fromJson(json),
      ),
    );
  }

  @override
  Future<Either<Failure, EnhancedTripResponse>> reprepareTrip(String token) {
    final data = {'prepare_token': token};
    return apiConsumer.handleRequest(
      () => apiConsumer.post(
        EndPoints.reprepareTrip,
        data: FormData.fromMap(data),
      ),
      (data) => EnhancedTripResponse.fromJson(data),
    );
  }

  @override
  Future<Either<Failure, EnhancedTripResponse>> newViewTrip(String token) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.newViewTrip(token)),
      (data) => EnhancedTripResponse.fromJson(data),
    );
  }
}
