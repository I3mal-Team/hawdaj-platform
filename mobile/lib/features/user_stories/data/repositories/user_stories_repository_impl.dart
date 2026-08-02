import 'dart:io';
import 'package:dartz/dartz.dart';
import 'package:flutter/foundation.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/user_stories/data/models/user_stories_response.dart';
import 'package:hawdaj/features/user_stories/data/repositories/user_stories_repository.dart';
import 'package:hawdaj/utils/form_data_helper.dart';

class UserStoriesRepositoryImpl implements UserStoriesRepository {
  final ApiConsumer apiConsumer;

  UserStoriesRepositoryImpl(this.apiConsumer);

  @override
  Future<Either<Failure, UserStoriesResponse>> fetchMyLastDayStories() {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.myLastDayStories),
      (data) => UserStoriesResponse.fromJson(data),
    );
  }

  @override
  Future<Either<Failure, Map<String, dynamic>>> addStory({
    String? title,
    required File file,
    required String fileType,
  }) async {
    /// 🖼️ Debug معلومات الملف قبل الرفع
    final filePath = file.path;
    final fileName = filePath.split('/').last;
    final fileSize = await file.length();

    debugPrint('📤 Story Upload Debug');
    debugPrint('📁 Local Path: $filePath');
    debugPrint('📄 File Name: $fileName');
    debugPrint('🧩 File Type: $fileType');
    debugPrint('📦 File Size: $fileSize bytes');

    /// لو حابب تطبع URI محلي
    final localUri = Uri.file(filePath);
    debugPrint('🔗 Local File URI: $localUri');

    /// (اختياري) طباعة الدومين المتوقع بعد الرفع
    final apiUri = Uri.parse(EndPoints.addStory);
    debugPrint('🌐 Upload API Domain: ${apiUri.host}');

    final FormDataHelper formDataHelper = FormDataHelper();

    // Required by API
    formDataHelper.addEntry('type', 'file');

    if (title != null && title.trim().isNotEmpty) {
      formDataHelper.addEntry('text', title.trim());
    }

    formDataHelper.addFile(file, fileKey: 'file');

    return apiConsumer.handleRequest(
      () => apiConsumer.post(
        EndPoints.addStory,
        data: formDataHelper.formDataRenderer,
        isFormData: true,
      ),
      (data) => data as Map<String, dynamic>,
    );
  }

  @override
  Future<Either<Failure, String>> removeStory({required int storyId}) {
    return apiConsumer.handleRequest(
      () => apiConsumer.delete(
        EndPoints.deleteStory(storyId),
        queryParameters: {'story_id': storyId},
      ),
      (data) => data['message'],
    );
  }
}
