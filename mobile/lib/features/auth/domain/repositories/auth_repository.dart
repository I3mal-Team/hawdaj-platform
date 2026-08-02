import 'dart:io';

import 'package:hawdaj/features/auth/data/models/user_model.dart';

import '../../data/models/auth_response_model.dart';
import '../../data/models/register_request_model.dart';
import '../../data/models/login_request_model.dart';
import '../../data/models/logout_response_model.dart';
import '../../data/models/update_profile_response_model.dart';
import '../../data/models/social_auth_request_model.dart';

abstract class AuthRepository {
  Future<AuthResponseModel> register(RegisterRequestModel registerRequest);
  Future<AuthResponseModel> login(LoginRequestModel loginRequest);
  Future<LogoutResponseModel> logout();
  //delete-profile
  Future<LogoutResponseModel> deleteProfile();
  //forgot-password
  Future<String> forgotPassword(String email);

  Future<UpdateProfileResponseModel> updateProfile({
    required String firstName,
    required String lastName,
    required String email,
    String? phone,
    String? gender,
    Map<String, String>? socialLinks,
  });
  Future<UserModel> updatePhoto(File imageFile);

  // Social Authentication Methods
  Future<AuthResponseModel> googleLogin(
    SocialAuthRequestModel socialAuthRequest,
  );
  Future<AuthResponseModel> appleLogin(
    SocialAuthRequestModel socialAuthRequest,
  );
  Future<AuthResponseModel> twitterLogin(
    SocialAuthRequestModel socialAuthRequest,
  );
}
