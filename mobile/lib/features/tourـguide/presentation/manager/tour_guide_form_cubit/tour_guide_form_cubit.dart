import 'dart:io';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/features/home/data/model/guide_model/guide_model.dart';
import 'package:image_picker/image_picker.dart';

part 'tour_guide_form_state.dart';

class TourGuideFormCubit extends Cubit<TourGuideFormState> {
  TourGuideFormCubit() : super(TourGuideFormState.initial());

  // ===== Basic fields =====
  void updateName(String value) => emit(state.copyWith(name: value));
  void updateNickName(String value) => emit(state.copyWith(nickName: value));
  void updateDescription(String value) =>
      emit(state.copyWith(description: value));
  void updateExperience(String value) =>
      emit(state.copyWith(experience: value));

  // ===== Collections =====
  void updateSelectedRegions(List<int> ids) =>
      emit(state.copyWith(selectedRegionIds: List<int>.from(ids)));

  void updateSelectedLanguages(List<int> ids) =>
      emit(state.copyWith(selectedLanguageIds: List<int>.from(ids)));

  // ===== Social links =====
  void addSocialLink(String? platform, String link) {
    if (platform == null || platform.trim().isEmpty) return;
    final newLinks = Map<String, String>.from(state.socialLinks);
    newLinks[platform] = link.trim();
    emit(state.copyWith(socialLinks: newLinks));
  }

  void editSocialLink({
    required String oldPlatform,
    required String newPlatform,
    required String link,
  }) {
    final map = Map<String, String>.from(state.socialLinks);
    if (oldPlatform != newPlatform) map.remove(oldPlatform);
    map[newPlatform] = link.trim();
    emit(state.copyWith(socialLinks: map));
  }

  void removeSocialLink(String platform) {
    final newLinks = Map<String, String>.from(state.socialLinks)
      ..remove(platform);
    emit(state.copyWith(socialLinks: newLinks));
  }

  void clearSocialLinks() => emit(state.copyWith(socialLinks: const {}));

  // ===== Image picking =====
  Future<void> setImageFromGallery() async {
    final picker = ImagePicker();
    final XFile? res = await picker.pickImage(source: ImageSource.gallery);
    if (res == null) return;
    emit(state.copyWith(imageFile: File(res.path)));
  }

  Future<void> setImageFromCamera() async {
    final picker = ImagePicker();
    final XFile? res = await picker.pickImage(source: ImageSource.camera);
    if (res == null) return;
    emit(state.copyWith(imageFile: File(res.path)));
  }

  void clearPickedImage() {
    emit(state.copyWith(resetImageFile: false));
  }

  // ===== Submit (payload preview) =====
  Map<String, dynamic> submit() {
    final payload = <String, dynamic>{
      'name': state.name.trim(),
      'nickName': state.nickName.trim(),
      'description': state.description.trim(),
      'experience': state.experience.trim(),
      'regions': state.selectedRegionIds,
      'languages': state.selectedLanguageIds,
      'socialLinks': state.socialLinks,
      'imageUrl': state.imageUrl, // لو API بيستخدم URL
      'hasLocalImage': state.imageFile != null,
      'localImagePath': state.imageFile?.path, // لو هتعمل Multipart
      'personalSite': state.socialLinks['Personal Site'],
    };
    // print(payload);
    return payload;
  }

  // ===== Reset =====
  void clear() {
    emit(TourGuideFormState.initial());
  }

  // ===== Init from model (Edit mode) =====
  /// لو الـ API بيرجع اسم ملف بس، مرّر [baseImageUrl] (مثلاً: EndPoints.imageUrl)
  void initForEditFromModel(GuideModel model, {String? baseImageUrl}) {
    // فلترة/تجهيز السوشيال
    final raw = model.social.toJson(); // SocialModel غير nullable في الموديل
    final filtered = <String, String>{};
    raw.forEach((key, value) {
      if (value == null) return;
      final v = value.toString().trim();
      if (v.isEmpty) return;
      filtered[key.toString()] = v;
    });

    // حلّ رابط الصورة
    final rawImage = model.image; // non-nullable في الموديل
    final resolvedImageUrl = rawImage.startsWith('http')
        ? rawImage
        : (baseImageUrl?.isNotEmpty == true
              ? _joinUrl(baseImageUrl!, rawImage)
              : rawImage);

    emit(
      state.copyWith(
        name: model.name,
        nickName: model.nickName,
        description: model.description,
        experience: model.experience.toString(),
        selectedRegionIds: model.regions.map((e) => e.id as int).toList(),
        selectedLanguageIds: model.languages.map((e) => e.id as int).toList(),
        socialLinks: filtered,
        imageUrl: resolvedImageUrl,
        resetImageFile: true, // مهم: مسح أي ملف محلي قديم
      ),
    );
  }

  // ===== Helpers =====
  String _joinUrl(String base, String path) {
    if (base.endsWith('/')) base = base.substring(0, base.length - 1);
    if (path.startsWith('/')) path = path.substring(1);
    return '$base/$path';
  }
}
