import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/landmarks/data/models/landmark_model.dart';
import 'package:hawdaj/features/landmarks/data/models/list_landmark_response.dart';

abstract class LandmarksRepository {
  Future<Either<Failure, CreateLandmarkResponse>> createLandmark(
    CreateLandmarkRequest request,
  );

  Future<Either<Failure, LandmarkData>> getLandmarks(int pageKey);

  Future<Either<Failure, LandmarkData>> getMyLandmarks(int pageKey);

  Future<Either<Failure, LandmarkItem>> getLandmark(int id);
}
