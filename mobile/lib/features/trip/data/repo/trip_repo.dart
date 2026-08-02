import 'package:dartz/dartz.dart';
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

abstract class TripRepo {
  Future<Either<Failure, List<PricesModel>>> fetchPrices();
  Future<Either<Failure, List<PricesModel>>> fetchCategory();
  //prepare
  Future<Either<Failure, TripModel>> prepareTrip(PrepareTripModel body);
  Future<Either<Failure, EnhancedTripResponse>> newPrepareTrip(
    NewTripPrepareParams body,
  );

  ///v2/trips/prepare/:token
  Future<Either<Failure, EnhancedTripResponse>> showPrepareTrip(
    final String token,
  );
  //prepare
  Future<Either<Failure, TripData>> finishTripDetails(final String token);
  //https://dashboard.hawdaj.net/api/trips/save-trip-to-email
  Future<Either<Failure, String>> saveTripToEmail(SaveTripToEmailParams body);
  //https://dashboard.hawdaj.net/api/trips/store
  Future<Either<Failure, String>> saveTrip(SaveTripToEmailParams body);
  Future<Either<Failure, PaginatedResponse<MyTripModel>>> myTrip(int page);
  Future<Either<Failure, PaginatedResponse<TripItem>>> newMyTrip(int page);
  Future<Either<Failure, String>> deleteTrip(final String token);
  //viewTrip
  Future<Either<Failure, TripDetailsModel>> viewTrip(final String token);
  //v2/trips/reprepare
  Future<Either<Failure, EnhancedTripResponse>> reprepareTrip(
    final String token,
  );
  //newViewTrip
  Future<Either<Failure, EnhancedTripResponse>> newViewTrip(final String token);
  //api/v2/trips/save
  Future<Either<Failure, String>> saveTripV2({
    required String token,
    required String name,
    required List<List<int>> items,
  });
}
