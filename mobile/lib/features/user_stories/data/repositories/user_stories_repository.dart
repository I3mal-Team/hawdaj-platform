import 'dart:io';
import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/user_stories/data/models/user_stories_response.dart';

abstract class UserStoriesRepository {
  Future<Either<Failure, UserStoriesResponse>> fetchMyLastDayStories();
  Future<Either<Failure, Map<String, dynamic>>> addStory({
    String? title,
    required File file,
    required String fileType,
  });
  //remove story
  Future<Either<Failure, String>> removeStory({required int storyId});
}
