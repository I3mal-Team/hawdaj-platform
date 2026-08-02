import 'package:dartz/dartz.dart';
import 'package:hawdaj/core/errors/failure.dart' show Failure;
import 'package:hawdaj/features/auth/data/models/user_model.dart';
import 'package:hawdaj/features/profile/data/model/favorite_model.dart';
import 'package:hawdaj/features/profile/data/model/profile_reponce.dart';
import 'package:hawdaj/features/profile/data/model/saved_item_model.dart';
import 'package:hawdaj/features/profile/data/model/story_model.dart';

abstract class ProfileRepo {
  Future<Either<Failure, UserModel>> updatePassword({
    required String currentPassword,
    required String password,
    required String passwordconfirmation,
  });
  Future<Either<Failure, ProfilePageResponse>> getProfile();
  Future<Either<Failure, List<FavoriteModel>>> getFavorites();
  Future<Either<Failure, List<SavedItemModel>>> getSaved();
  Future<Either<Failure, List<StoryModel>>> myLastDayStories();
}
