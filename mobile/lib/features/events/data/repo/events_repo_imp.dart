import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/events/data/repo/events_repo.dart';
import 'package:hawdaj/features/home/data/model/top_events_model/event_model.dart';

class EventsRepoImp implements EventsRepo {
  final ApiConsumer apiConsumer;

  EventsRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, EventModel>> fetchEvents(String slug) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.detailsEvents(slug)),
      (data) => EventModel.fromJson(data['data']),
    );
  }
}
