import 'dart:io';
import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/core/errors/exceptions.dart';
import 'package:hawdaj/features/landmarks/data/models/landmark_model.dart';
import 'package:hawdaj/features/landmarks/data/models/list_landmark_response.dart';
import 'package:hawdaj/features/landmarks/data/repositories/landmarks_repository_repo.dart';
import 'package:hawdaj/utils/form_data_helper.dart';

class LandmarksRepositoryImpl implements LandmarksRepository {
  final ApiConsumer apiConsumer;

  LandmarksRepositoryImpl({required this.apiConsumer});

  @override
  Future<Either<Failure, CreateLandmarkResponse>> createLandmark(
    CreateLandmarkRequest request,
  ) async {
    try {
      // Create FormData for file upload
      FormDataHelper formDataHelper = FormDataHelper();

      // Add text fields according to the API specification
      formDataHelper.addEntry('title', request.name);
      formDataHelper.addEntry('description', request.description);
      formDataHelper.addEntry('address', request.address);
      formDataHelper.addEntry('type', request.type); // Fixed type as per API

      // Add images if any
      if (request.images.isNotEmpty) {
        for (String imagePath in request.images) {
          File imageFile = File(imagePath);
          if (await imageFile.exists()) {
            await formDataHelper.addFile(
              imageFile,
              fileKey: 'image', // Single image field as per API
            );
          }
        }
      }

      return await apiConsumer.handleRequest(
        () => apiConsumer.post(
          EndPoints.landmarkStore,
          data: formDataHelper.formDataRenderer,
          isFormData: true, // Use FormData as specified
        ),
        (p0) => CreateLandmarkResponse.fromJson(p0),
      );
    } catch (e) {
      return Left(ServerFailure(errMessage: e.toString()));
    }
  }

  @override
  Future<Either<Failure, LandmarkData>> getLandmarks(int pageKey) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.landmarkList(pageKey)),
      (json) => LandmarkData.fromJson(json['data']),
    );
  }

  @override
  Future<Either<Failure, LandmarkData>> getMyLandmarks(int pageKey) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.myLandMarks(pageKey)),
      (json) => LandmarkData.fromJson(json['data']),
    );
  }

  //landmark/show/:id
  @override
  Future<Either<Failure, LandmarkItem>> getLandmark(int id) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.landmarkShow(id)),
      (json) => LandmarkItem.fromJson(json['data']),
    );
  }
}
