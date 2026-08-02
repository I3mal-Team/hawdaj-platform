import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/top_events_model/event_model.dart';

abstract class EventsRepo {
  Future<Either<Failure, EventModel>> fetchEvents(String slug);
}
