import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/databases/api/api_consumer.dart';
import 'package:hawdaj/core/databases/api/api_consumer_extension.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/errors/failure.dart';
import 'package:hawdaj/features/auth/data/models/user_model.dart';
import 'package:hawdaj/features/profile/data/model/favorite_model.dart';
import 'package:hawdaj/features/profile/data/model/profile_reponce.dart';
import 'package:hawdaj/features/profile/data/model/saved_item_model.dart';
import 'package:hawdaj/features/profile/data/model/story_model.dart';

import 'package:hawdaj/features/profile/data/repo/profail_repo.dart';

class ProfileRepoImp implements ProfileRepo {
  final ApiConsumer apiConsumer;

  ProfileRepoImp(this.apiConsumer);

  @override
  Future<Either<Failure, UserModel>> updatePassword({
    required String currentPassword,
    required String password,
    required String passwordconfirmation,
  }) {
    return apiConsumer.handleRequest(
      () => apiConsumer.post(
        EndPoints.updatePassword,
        data: {
          'currentPassword': currentPassword,
          'password': password,
          'password_confirmation': passwordconfirmation,
        },
      ),
      (data) => UserModel.fromJson(data['data']),
    );
  }

  @override
  Future<Either<Failure, ProfilePageResponse>> getProfile() {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.profile),
      (data) => ProfilePageResponse.fromJson(data),
    );
  }

  @override
  Future<Either<Failure, List<FavoriteModel>>> getFavorites() {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.favorites),
      (data) {
        final List items = data['data']['items'] ?? [];
        return items.map((e) => FavoriteModel.fromJson(e)).toList();
      },
    );
  }

  @override
  Future<Either<Failure, List<SavedItemModel>>> getSaved() {
    return apiConsumer.handleRequest(() => apiConsumer.get(EndPoints.mySaved), (
      data,
    ) {
      final List items = data['data']['items'] ?? [];
      return items.map((e) => SavedItemModel.fromJson(e)).toList();
    });
  }

  @override
  Future<Either<Failure, List<StoryModel>>> myLastDayStories() {
    return apiConsumer.handleRequest(
      () => apiConsumer.get(EndPoints.storiesmyLastDayStories),
      (data) {
        final List items = data['data']['items'] ?? [];
        return items.map((e) => StoryModel.fromJson(e)).toList();
      },
    );
  }
}
