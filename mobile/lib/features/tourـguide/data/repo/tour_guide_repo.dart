import 'dart:io';

import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_response_model.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/model/update_guide_photo_model.dart';

abstract class TourGuideRepo {
  Future<Either<Failure, GuideModel>> fetchTourGuide(int id);

  Future<Either<Failure, UnifiedResponseModel>> fetchTourGuideByTopRated(
    int page,
    int perPage,
    int excludedId,
    bool topRated,
  );
  Future<Either<Failure, GuideModel>> fetchAllTourGuide();

  Future<Either<Failure, List<RegionModel>>> fetchLanguages();
  Future<Either<Failure, String>> updateGuidePhoto({required File? image});

  Future<Either<Failure, List<RegionModel>>> fetchRegion();
  //updateGuidePhoto

  Future<Either<Failure, GuideModel>> storeGuide(
    String name,
    String nickName,
    String description,
    String experience,
    String facebook,
    String instagram,
    String twitter,
    String linkedin,
    List<int> regions,
    List<int> languages,
    String PersonalSite,
    String youtube,
    String tiktok,
  );
}
