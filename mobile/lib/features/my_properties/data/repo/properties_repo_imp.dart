import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/my_properties/data/model/create_property_request.dart';
import 'package:hawdaj/features/my_properties/data/model/my_properties_response.dart';
import 'package:hawdaj/features/my_properties/data/repo/properties_repo.dart';

class PropertiesRepoImp implements PropertiesRepo {
  final ApiConsumer apiConsumer;

  PropertiesRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, String>> addProperties(
    CreatePropertyRequest param,
  ) async {
    // 🧾 طباعة تفصيلية لمحتوى الـ param قبل الإرسال
    print('──────────────────────────────');
    print('📦 [PropertiesRepoImp] Request data before toFormData():');
    print('──────────────────────────────');
    print('• Type: ${param.type}');
    print('• Title: ${param.title}');
    print('• Description: ${param.description}');
    print('• Address: ${param.address}');
    print('• Lat: ${param.lat}');
    print('• Long: ${param.long}');
    print('• Region ID: ${param.regionId}');
    print('• City ID: ${param.cityId}');
    print('• WhatsApp: ${param.whatsapp}');
    print('• Facebook: ${param.facebookLink}');
    print('• Instagram: ${param.instagramLink}');
    print('• Website: ${param.websiteLink}');
    print('• Categories: ${param.categories}');
    print('• Seasons: ${param.seasons}');
    print('• Price ID: ${param.priceId}');
    print('• Ticket Link: ${param.ticketLink}');
    print('• Video URL: ${param.videoUrl}');
    print('• Date From: ${param.dateFrom}');
    print('• Date To: ${param.dateTo}');
    print('• Food Categories: ${param.foodCategories}');
    print('• Ownership Proof File: ${param.ownershipProofFile}');
    print('• Images: ${param.imagePaths}');
    print('• Menu Files: ${param.menuFile}');
    print('──────────────────────────────');

    // تحويل إلى FormData
    final formData = await param.toFormData();

    // طباعة عدد الحقول بعد التحويل
    print(
      '✅ FormData ready with ${formData.fields.length} fields and ${formData.files.length} files',
    );
    for (final f in formData.fields) {
      print('→ ${f.key}: ${f.value}');
    }

    // تنفيذ الطلب
    return apiConsumer.handleRequest(
      () => apiConsumer.post(EndPoints.properties, data: formData),
      (data) => data['message'] as String,
    );
  }

  @override
  Future<Either<Failure, MyPropertiesResponse>> getMyProperties() async {
    print('📡 Fetching my properties...');
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.myProperties),
      (data) => MyPropertiesResponse.fromJson(data),
    );
  }
}
