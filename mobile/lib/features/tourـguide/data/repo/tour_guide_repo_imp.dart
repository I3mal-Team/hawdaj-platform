import 'dart:io';

import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';
import 'package:hawdaj/features/tasneef/data/models/region_model.dart';
import 'package:hawdaj/features/tasneef/data/models/unified_response_model.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/model/update_guide_photo_model.dart';
import 'package:hawdaj/features/tour%D9%80guide/data/repo/tour_guide_repo.dart';
import 'package:hawdaj/utils/form_data_helper.dart';

class TourGuideRepoImp implements TourGuideRepo {
  final ApiConsumer apiConsumer;

  TourGuideRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, GuideModel>> fetchTourGuide(int id) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.detailsTourGuide(id)),
      (data) => GuideModel.fromJson(data['data']),
    );
  }

  @override
  Future<Either<Failure, UnifiedResponseModel>> fetchTourGuideByTopRated(
    int page,
    int perPage,
    int excludedId,
    bool topRated,
  ) {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(
        EndPoints.tourGuideByTopRated(),
        queryParameters: {
          'page': page,
          'per_page': perPage,
          'excludedId': excludedId,
          'top_rated': topRated,
        },
      ),
      (data) => UnifiedResponseModel.fromJson(data),
    );
  }

  @override
  Future<Either<Failure, GuideModel>> fetchAllTourGuide() {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.tourGuides),
      (data) => GuideModel.fromJson(data['data']['userGuide']),
    );
  }

  @override
  Future<Either<Failure, List<RegionModel>>> fetchLanguages() {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.languages),
      (data) {
        final list = data['data'] as List;
        return list.map((e) => RegionModel.fromJson(e)).toList();
      },
    );
  }

  @override
  Future<Either<Failure, List<RegionModel>>> fetchRegion() {
    return apiConsumer.handleRequest(() => apiConsumer.get(EndPoints.region), (
      data,
    ) {
      final list = data['data'] as List;
      return list.map((e) => RegionModel.fromJson(e)).toList();
    });
  }

  @override
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
  ) {
    // تطبيع الروابط: إضافة https:// لو ناقصة، وإرجاع '' لو فاضي
    String _norm(String? url) {
      if (url == null) return '';
      final u = url.trim();
      if (u.isEmpty) return '';
      if (u.startsWith('http://') || u.startsWith('https://')) return u;
      return 'https://$u';
    }

    // تجربة تحويل الخبرة لرقم (اختياري)
    final exp = int.tryParse(experience.trim());

    // جسم الطلب — نضيف الاختياري بس لو له قيمة
    final body = {
      'name': name.trim(),
      'nickName': nickName.trim(),
      'description': description.trim(),
      if (exp != null) 'experience': exp,
      'regions': regions,
      'languages': languages,

      if (_norm(facebook).isNotEmpty) 'facebook': _norm(facebook),
      if (_norm(instagram).isNotEmpty) 'instagram': _norm(instagram),
      if (_norm(twitter).isNotEmpty) 'twitter': _norm(twitter),
      if (_norm(linkedin).isNotEmpty) 'linkedin': _norm(linkedin),
      if (_norm(youtube).isNotEmpty) 'youtube': _norm(youtube),
      if (_norm(tiktok).isNotEmpty) 'tiktok': _norm(tiktok),

      if (_norm(PersonalSite).isNotEmpty) 'Personal Site': _norm(PersonalSite),
    };

    return apiConsumer.handleRequest(
      () => apiConsumer.post(EndPoints.storeGuide, data: body),
      (data) {
        final dynamic payload = data['data'];
        final dynamic json = (payload is Map && payload['userGuide'] != null)
            ? payload['userGuide']
            : payload;
        return GuideModel.fromJson(json as Map<String, dynamic>);
      },
    );
  }

  @override
  Future<Either<Failure, String>> updateGuidePhoto({
    required File? image,
  }) async {
    var helper = FormDataHelper();
    await helper.addFile(image, fileKey: 'image'); // ← حسب اسم الباك إند

    return apiConsumer.handleRequest(
      () => apiConsumer.post(
        EndPoints.updateGuidePhoto,
        data: helper.formDataRenderer, // ✅ مش image
        isFormData: true,
      ),
      (data) => data['message'] ?? 'تم تحديث الصورة',
    );
  }
}
