import 'dart:io';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:hawdaj/core/utils/functions/camera_helper.dart';
import 'package:hawdaj/features/auth/data/models/user_model.dart';
import 'package:hawdaj/features/auth/domain/repositories/auth_repository.dart';
import 'package:hawdaj/features/profile/data/model/profile_reponce.dart';

part 'edit_profile_state.dart';

class EditProfileCubit extends Cubit<EditProfileState> {
  final AuthRepository _authRepository;

  final TextEditingController firstNameController = TextEditingController();
  final TextEditingController lastNameController = TextEditingController();
  final TextEditingController emailController = TextEditingController();
  final TextEditingController phoneController = TextEditingController();

  EditProfileCubit(this._authRepository) : super(const EditProfileState()) {
    _initializeControllerListeners();
  }

  void _initializeControllerListeners() {
    firstNameController.addListener(() {
      if (state.isInitialized) {
        updateFirstName(firstNameController.text);
      }
    });

    lastNameController.addListener(() {
      if (state.isInitialized) {
        updateLastName(lastNameController.text);
      }
    });

    phoneController.addListener(() {
      if (state.isInitialized) {
        updatePhone(phoneController.text.isEmpty ? null : phoneController.text);
      }
    });
  }

  void initializeProfile(ProfilePageResponse model) {
    final user = model.data.personalData;

    firstNameController.text = user.firstName ?? '';
    lastNameController.text = user.lastName ?? '';
    emailController.text = user.email ?? '';
    phoneController.text = user.phone ?? '';

    final Map<String, String> links = {};

    if (model.data.social.isNotEmpty) {
      final social = model.data.social.first;

      final Map<String, String?> socialMap = {
        'twitter': social.twitter,
        'instagram': social.instagram,
        'linkedin': social.linkedin,
        'youtube': social.youtube,
        'tiktok': social.tiktok,
        'personal_account': null,
      };

      socialMap.forEach((key, value) {
        if (value != null && value.trim().isNotEmpty) {
          links[_normalizePlatformKey(key)] = value.trim();
        }
      });
    }

    print('✅ روابط السوشيال المحملة: $links');

    emit(
      state.copyWith(
        firstName: user.firstName ?? '',
        lastName: user.lastName ?? '',
        email: user.email ?? '',
        phone: user.phone,
        gender: user.gender,
        isInitialized: true,
        socialLinks: links,
        imageUrl: user.photo,
      ),
    );
  }

  String _normalizePlatformKey(String rawKey) {
    switch (rawKey) {
      case 'twitter':
        return 'X';
      case 'instagram':
        return 'Instagram';
      case 'linkedin':
        return 'LinkedIn';
      case 'youtube':
        return 'YouTube';
      case 'tiktok':
        return 'TikTok';
      case 'personal_account':
        return 'Personal Site';
      default:
        return rawKey;
    }
  }

  void updateFirstName(String firstName) {
    emit(state.copyWith(firstName: firstName));
  }

  void updateLastName(String lastName) {
    emit(state.copyWith(lastName: lastName));
  }

  void updatePhone(String? phone) {
    emit(state.copyWith(phone: phone));
  }

  void updateGender(String? gender) {
    emit(state.copyWith(gender: gender));
  }

  void addSocialLink(String platform, String link) {
    final updated = Map<String, String>.from(state.socialLinks)
      ..[platform] = link;
    emit(state.copyWith(socialLinks: updated));
  }

  void editSocialLink({
    required String oldPlatform,
    required String newPlatform,
    required String link,
  }) {
    final updated = Map<String, String>.from(state.socialLinks);
    updated.remove(oldPlatform);
    updated[newPlatform] = link;
    emit(state.copyWith(socialLinks: updated));
  }

  void removeSocialLink(String platform) {
    final updated = Map<String, String>.from(state.socialLinks)
      ..remove(platform);
    emit(state.copyWith(socialLinks: updated));
  }

  Future<void> updateProfile() async {
    if (state.isLoading) return;

    emit(state.copyWith(isLoading: true, errorMessage: null));
    if (state.imageFile != null) {
      final file = state.imageFile!;
      print('✅ صورة جديدة جاهزة للرفع:');
      print('📁 المسار: ${file.path}');
      print('📄 الاسم: ${file.path.split('/').last}');
      print('📦 الحجم: ${await file.length()} bytes');
    }

    try {
      final Map<String, String?> normalizedSocial = {};
      for (final entry in state.socialLinks.entries) {
        final backendKey = _platformToBackendKey(entry.key);
        normalizedSocial[backendKey] = entry.value;
      }
      final Map<String, String> filteredSocial = {
        for (var entry in normalizedSocial.entries)
          if (entry.value != null && entry.value!.isNotEmpty)
            entry.key: entry.value!,
      };

      final response = await _authRepository.updateProfile(
        firstName: state.firstName,
        lastName: state.lastName,
        email: state.email,
        phone: state.phone,
        gender: state.gender,
        socialLinks: filteredSocial,
      );

      if (response.success) {
        emit(
          state.copyWith(
            isLoading: false,
            isSuccess: true,
            updatedUser: response.data?.personalData,
          ),
        );
      } else {
        emit(
          state.copyWith(
            isLoading: false,
            errorMessage: response.message?.isNotEmpty == true
                ? response.message!
                : 'Failed to update profile',
          ),
        );
      }
    } catch (e) {
      emit(state.copyWith(isLoading: false, errorMessage: e.toString()));
      if (kDebugMode) rethrow;
    }
  }

  Future<void> submit() async {
    if (state.isLoading) return;

    if (state.imageFile != null) {
      await updatePhoto(); // لو فيه صورة
    } else {
      await updateProfile(); // لو مفيش صورة
    }
  }

  Future<void> updatePhoto() async {
    if (state.isLoading) return;

    emit(state.copyWith(isLoading: true, errorMessage: null, isSuccess: false));

    try {
      final file = state.imageFile!;
      print('✅ صورة جديدة جاهزة للرفع: ${file.path}');
      print('📄 الاسم: ${file.path.split('/').last}');
      print('📦 الحجم: ${await file.length()} bytes');

      // الريبو بيرجع UserModel مباشر
      final UserModel user = await _authRepository.updatePhoto(file);

      // Clear cached images to ensure new image is loaded
      if (state.imageUrl != null && state.imageUrl!.isNotEmpty) {
        final oldImageUrl = state.imageUrl!.startsWith('http')
            ? state.imageUrl!
            : '${state.imageUrl!}';
        await CachedNetworkImage.evictFromCache(oldImageUrl);
      }

      if (user.photo != null && user.photo!.isNotEmpty) {
        print('🟢 user.photo (raw from API): ${user.photo}');

        final bool isFullUrl = user.photo!.startsWith('http');
        print('🔍 هل الرابط كامل؟ $isFullUrl');

        final newImageUrl = isFullUrl ? user.photo! : '${user.photo!}';

        print('🌐 final image url used for cache: $newImageUrl');

        final wasRemoved = await CachedNetworkImage.evictFromCache(newImageUrl);

        print(
          wasRemoved
              ? '✅ Image cache cleared successfully'
              : '⚠️ Image was NOT found in cache',
        );
      } else {
        print('❌ user.photo is null or empty');
      }

      emit(
        state.copyWith(
          isLoading: false,
          isSuccess: true,
          updatedUser: user,
          imageFile: null, // تنظيف الصورة المختارة بعد الرفع
          imageUrl: user.photo, // تحديث رابط الصورة من السيرفر
        ),
      );
    } catch (e) {
      emit(state.copyWith(isLoading: false, errorMessage: e.toString()));
      if (kDebugMode) rethrow;
    }
  }

  // تحويل من display key للـ backend key
  String _platformToBackendKey(String platform) {
    switch (platform) {
      case 'X':
        return 'twitter';
      case 'Instagram':
        return 'instagram';
      case 'LinkedIn':
        return 'linkedin';
      case 'YouTube':
        return 'youtube';
      case 'TikTok':
        return 'tiktok';
      case 'Personal Site':
        return 'personal_account';
      default:
        return platform.toLowerCase().replaceAll(' ', '_');
    }
  }

  void resetState() {
    emit(const EditProfileState());
  }

  void showGenderSelection(BuildContext context) {
    showModalBottomSheet(
      context: context,
      builder: (BuildContext context) {
        return Container(
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text(
                'اختر النوع',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.w800),
              ),
              const SizedBox(height: 20),
              ListTile(
                title: const Text('ذكر', style: TextStyle(fontSize: 16)),
                onTap: () {
                  updateGender('male');
                  if (context.canPop()) context.pop();
                },
              ),
              ListTile(
                title: const Text('أنثى', style: TextStyle(fontSize: 16)),
                onTap: () {
                  updateGender('female');
                  if (context.canPop()) context.pop();
                },
              ),
            ],
          ),
        );
      },
    );
  }

  Future<void> setImageFromGallery() async {
    try {
      final file = await CameraHelper.pickPhotoFromGallery();
      if (file != null) {
        emit(state.copyWith(imageFile: file, errorMessage: null));
      }
    } catch (e) {
      String errorMessage = 'فشل في تحديد صورة من المعرض. تأكد من الأذونات.';
      if (e is CameraException) {
        errorMessage = e.message.contains('permission')
            ? 'يجب السماح بالوصول للصور لتحديد صورة من المعرض'
            : 'فشل في تحديد صورة من المعرض';
      }

      emit(state.copyWith(errorMessage: errorMessage));
      if (kDebugMode) {
        print('Gallery picker error: $e');
      }
    }
  }

  Future<void> setImageFromCamera(BuildContext context) async {
    try {
      final file = await CameraHelper.takePhotoWithCamera(context);
      if (file != null) {
        emit(state.copyWith(imageFile: file, errorMessage: null));
      }
    } catch (e) {
      String errorMessage = 'فشل في التقاط صورة بالكاميرا. تأكد من الأذونات.';
      if (e is CameraException) {
        errorMessage = e.message.contains('permission')
            ? 'يجب السماح بالوصول للكاميرا لالتقاط صورة'
            : 'فشل في التقاط صورة بالكاميرا';
      }

      emit(state.copyWith(errorMessage: errorMessage));
      if (kDebugMode) {
        print('Camera picker error: $e');
      }
    }
  }

  void clearPickedImage() {
    emit(state.copyWith(imageFile: null));
  }

  @override
  Future<void> close() {
    firstNameController.dispose();
    lastNameController.dispose();
    emailController.dispose();
    phoneController.dispose();
    return super.close();
  }
}
