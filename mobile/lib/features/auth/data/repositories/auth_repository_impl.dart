import 'dart:io';

import 'package:dio/dio.dart';
import 'package:hawdaj/features/auth/data/models/user_model.dart';
import '../../../../core/databases/api/api_consumer.dart';
import '../../../../core/databases/api/end_points.dart';
import '../../../../core/utils/auth_manager.dart';
import '../../domain/repositories/auth_repository.dart';
import '../models/auth_response_model.dart';
import '../models/register_request_model.dart';
import '../models/login_request_model.dart';
import '../models/logout_response_model.dart';
import '../models/update_profile_response_model.dart';
import '../models/social_auth_request_model.dart';

class AuthRepositoryImpl implements AuthRepository {
  final ApiConsumer apiConsumer;

  AuthRepositoryImpl({required this.apiConsumer});

  @override
  Future<AuthResponseModel> register(
    RegisterRequestModel registerRequest,
  ) async {
    try {
      // Debug: Print the data being sent
      print('Registration data: ${registerRequest.toJson()}');

      // Create form data directly
      final formData = FormData.fromMap(registerRequest.toJson());

      // Debug: Print form data fields
      print(
        'FormData fields: ${formData.fields.map((e) => '${e.key}: ${e.value}').join(', ')}',
      );

      // Make the API call
      final response = await apiConsumer.post(
        EndPoints.register,
        data: formData,
        isFormData: true,
      );

      // Parse the response
      return AuthResponseModel.fromJson(response);
    } catch (e) {
      print('Registration error: $e');
      // Handle DioException and other errors
      if (e is DioException) {
        print('DioException response: ${e.response?.data}');
        if (e.response != null) {
          // Parse error response
          return AuthResponseModel.fromJson(e.response!.data);
        }
      }
      rethrow;
    }
  }

  @override
  Future<AuthResponseModel> login(LoginRequestModel loginRequest) async {
    try {
      // Debug: Print the data being sent
      print('Login data: ${loginRequest.toJson()}');

      // Create form data for login
      final formData = FormData.fromMap(loginRequest.toJson());

      // Debug: Print form data fields
      print(
        'FormData fields: ${formData.fields.map((e) => '${e.key}: ${e.value}').join(', ')}',
      );

      // Make the API call
      final response = await apiConsumer.post(
        EndPoints.login,
        data: formData,
        isFormData: true,
      );

      // Parse the response
      var model = AuthResponseModel.fromJson(response);

      return model;
    } catch (e) {
      print('Login error: $e');
      // Handle DioException and other errors
      if (e is DioException) {
        print('Login DioException response: ${e.response?.data}');
        if (e.response != null) {
          // Parse error response
          return AuthResponseModel.fromJson(e.response!.data);
        }
      }
      rethrow;
    }
  }

  @override
  Future<LogoutResponseModel> logout() async {
    try {
      // Get token for Authorization header
      final token = await AuthManager.getToken();
      print('Logout with token: $token');

      // Make the API call with Authorization header
      final response = await apiConsumer.post(
        EndPoints.logout,
        data: {},
        isFormData: false, // Use JSON for logout
      );

      // Parse the response
      return LogoutResponseModel.fromJson(response);
    } catch (e) {
      print('Logout error: $e');
      // Handle DioException and other errors
      if (e is DioException) {
        print('Logout DioException response: ${e.response?.data}');
        if (e.response != null) {
          // Parse error response
          return LogoutResponseModel.fromJson(e.response!.data);
        }
      }
      rethrow;
    }
  }

  @override
  Future<UpdateProfileResponseModel> updateProfile({
    required String firstName,
    required String lastName,
    required String email,
    String? phone,
    String? gender,
    Map<String, String>? socialLinks, // ✅ الجديد
  }) async {
    try {
      String _norm(String? url) {
        if (url == null) return '';
        final u = url.trim();
        if (u.isEmpty) return '';
        if (u.startsWith('http://') || u.startsWith('https://')) return u;
        return 'https://$u';
      }

      final token = await AuthManager.getToken();
      print('Update profile with token: $token');

      final Map<String, dynamic> requestData = {
        'first_name': firstName,
        'last_name': lastName,
        'email': email,
        'phone': phone,
        'gender': gender,
      };

      // أضف روابط التواصل لو موجودة
      if (socialLinks != null && socialLinks.isNotEmpty) {
        socialLinks.forEach((key, value) {
          final normalized = _norm(value);
          if (normalized.isNotEmpty) {
            requestData[key] = normalized;
          }
        });
      }

      print('Update profile data: $requestData');

      final response = await apiConsumer.post(
        EndPoints.updateProfile,
        data: requestData,
        isFormData: false,
      );

      return UpdateProfileResponseModel.fromJson(response);
    } catch (e) {
      print('Update profile error: $e');
      if (e is DioException) {
        print('Update profile DioException response: ${e.response?.data}');
        if (e.response != null) {
          return UpdateProfileResponseModel.fromJson(e.response!.data);
        }
      }
      rethrow;
    }
  }

  @override
  Future<UserModel> updatePhoto(File imageFile) async {
    try {
      final token = await AuthManager.getToken();
      print('📤 Upload photo with token: $token');
      print('📁 Image file: ${imageFile.path}');

      final formData = FormData.fromMap({
        'photo': await MultipartFile.fromFile(
          imageFile.path,
          filename: imageFile.path.split('/').last,
        ),
      });

      final response = await apiConsumer.post(
        EndPoints.updatePhoto,
        data: formData,
        isFormData: true,
      );

      print('✅ updatePhoto full response: $response');
      print('📸 Photo URL from server: ${response['data']['photo']}');

      // Fix for backend returning production domain in test environment
      if (EndPoints.baseUrl.contains('test') &&
          response['data']['photo'] != null &&
          response['data']['photo'].toString().contains(
            'https://dashboard.hawdaj.net',
          )) {
        response['data']['photo'] = response['data']['photo']
            .toString()
            .replaceFirst(
              'https://dashboard.hawdaj.net',
              'https://test.dashboard.hawdaj.net',
            );
        print('🔧 Fixed Photo URL: ${response['data']['photo']}');
      }

      final userModel = UserModel.fromJson(response['data']);
      print('👤 UserModel photo after parsing: ${userModel.photo}');

      return userModel;
    } catch (e) {
      print('❌ Update photo error: $e');
      if (e is DioException && e.response != null) {
        print('❌ Error response: ${e.response!.data}');
        return UserModel.fromJson(e.response!.data['data']);
      }
      rethrow;
    }
  }

  @override
  Future<LogoutResponseModel> deleteProfile() async {
    try {
      // Get token for Authorization header
      final token = await AuthManager.getToken();
      print('Logout with token: $token');

      // Make the API call with Authorization header
      final response = await apiConsumer.delete(
        EndPoints.deleteProfile,
        data: {},
      );

      // Parse the response
      return LogoutResponseModel.fromJson(response);
    } catch (e) {
      print('Logout error: $e');
      // Handle DioException and other errors
      if (e is DioException) {
        print('Logout DioException response: ${e.response?.data}');
        if (e.response != null) {
          // Parse error response
          return LogoutResponseModel.fromJson(e.response!.data);
        }
      }
      rethrow;
    }
  }

  @override
  Future<AuthResponseModel> googleLogin(
    SocialAuthRequestModel socialAuthRequest,
  ) async {
    try {
      // Debug: Print the data being sent
      print('Google login data: ${socialAuthRequest.toJson()}');

      // Create form data for social login
      final formData = FormData.fromMap(socialAuthRequest.toJson());

      // Debug: Print form data fields
      print(
        'FormData fields: ${formData.fields.map((e) => '${e.key}: ${e.value}').join(', ')}',
      );

      // Make the API call
      final response = await apiConsumer.post(
        EndPoints.googleLogin,
        data: formData,
        isFormData: true,
      );

      // Parse the response
      return AuthResponseModel.fromJson(response);
    } catch (e) {
      print('Google login error: $e');
      // Handle DioException and other errors
      if (e is DioException) {
        print('Google login DioException response: ${e.response?.data}');
        if (e.response != null) {
          // Parse error response
          return AuthResponseModel.fromJson(e.response!.data);
        }
      }
      rethrow;
    }
  }

  @override
  Future<AuthResponseModel> appleLogin(
    SocialAuthRequestModel socialAuthRequest,
  ) async {
    try {
      // Debug: Print the data being sent
      print('Apple login data: ${socialAuthRequest.toJson()}');

      // Create form data for social login
      final formData = FormData.fromMap(socialAuthRequest.toJson());

      // Debug: Print form data fields
      print(
        'FormData fields: ${formData.fields.map((e) => '${e.key}: ${e.value}').join(', ')}',
      );

      // Make the API call
      final response = await apiConsumer.post(
        EndPoints.appleLogin,
        data: formData,
        isFormData: true,
      );

      // Parse the response
      return AuthResponseModel.fromJson(response);
    } catch (e) {
      print('Apple login error: $e');
      // Handle DioException and other errors
      if (e is DioException) {
        print('Apple login DioException response: ${e.response?.data}');
        if (e.response != null) {
          // Parse error response
          return AuthResponseModel.fromJson(e.response!.data);
        }
      }
      rethrow;
    }
  }

  @override
  Future<AuthResponseModel> twitterLogin(
    SocialAuthRequestModel socialAuthRequest,
  ) async {
    try {
      // Debug: Print the data being sent
      print('Twitter login data: ${socialAuthRequest.toJson()}');

      // Create form data for social login
      final formData = FormData.fromMap(socialAuthRequest.toJson());

      // Debug: Print form data fields
      print(
        'FormData fields: ${formData.fields.map((e) => '${e.key}: ${e.value}').join(', ')}',
      );

      // Make the API call
      final response = await apiConsumer.post(
        EndPoints.twitterLogin,
        data: formData,
        isFormData: true,
      );

      // Parse the response
      return AuthResponseModel.fromJson(response);
    } catch (e) {
      print('Twitter login error: $e');
      // Handle DioException and other errors
      if (e is DioException) {
        print('Twitter login DioException response: ${e.response?.data}');
        if (e.response != null) {
          // Parse error response
          return AuthResponseModel.fromJson(e.response!.data);
        }
      }
      rethrow;
    }
  }

  @override
  Future<String> forgotPassword(String email) async {
    try {
      final response = await apiConsumer.post(
        EndPoints.forgotPassword,
        data: {'emailAddress': email},
      );
      return response['message'] ?? 'تم إرسال رابط استعادة كلمة المرور';
    } catch (e) {
      print('Forgot password error: $e');
      if (e is DioException) {
        print('Forgot password DioException response: ${e.response?.data}');
        if (e.response != null && e.response?.data is Map) {
          final errorMessage =
              e.response!.data['message'] ?? 'حدث خطأ في الخادم';
          throw Exception(errorMessage); // ✅ بدل return
        }
      }
      throw Exception('حدث خطأ غير متوقع، حاول مرة أخرى'); // ✅ بدل rethrow عام
    }
  }

  Future<String> verifyEmailCode(String code, String email) async {
    try {
      final response = await apiConsumer.post(
        EndPoints.verifyEmailCode,
        data: {'code': code, 'emailAddress': email},
      );
      return response['message'] ?? 'تم التحقق بنجاح';
    } catch (e) {
      print('Verify email code error: $e');
      if (e is DioException) {
        print('Verify email code DioException response: ${e.response?.data}');
        if (e.response != null && e.response?.data is Map) {
          final errorMessage =
              e.response!.data['message'] ?? 'حدث خطأ في الخادم';
          throw Exception(errorMessage);
        }
      }
      throw Exception('حدث خطأ غير متوقع، حاول مرة أخرى'); // ✅ بدل rethrow عام
    }
  }
  //reset-password

  // Future<String> resetPassword(String email) async {
  //   try {
  //     final response = await apiConsumer.post(
  //       EndPoints.resetPassword,
  //       data: {'emailAddress': email},
  //     );
  //     return response['message'] ?? 'تم استعادة كلمة المرور بنجاح';
  //   } catch (e) {
  //     print('Reset password error: $e');
  //     if (e is DioException) {
  //       print('Reset password DioException response: ${e.response?.data}');
  //       if (e.response != null && e.response?.data is Map) {
  //         final errorMessage =
  //             e.response!.data['message'] ?? 'حدث خطأ في الخادم';
  //         throw Exception(errorMessage);
  //       }
  //     }
  //     throw Exception('حدث خطأ غير متوقع، حاول مرة أخرى'); // ✅ بدل rethrow عام
  //   }
}
