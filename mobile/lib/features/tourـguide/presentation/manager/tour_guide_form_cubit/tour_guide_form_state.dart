part of 'tour_guide_form_cubit.dart';

class TourGuideFormState extends Equatable {
  final String name;
  final String nickName;
  final String description;
  final String experience;
  final List<int> selectedRegionIds;
  final List<int> selectedLanguageIds;
  final Map<String, String> socialLinks;

  final String imageUrl; // شبكة
  final File? imageFile; // محلي

  const TourGuideFormState({
    required this.name,
    required this.nickName,
    required this.description,
    required this.experience,
    required this.selectedRegionIds,
    required this.selectedLanguageIds,
    required this.socialLinks,
    this.imageUrl = '',
    this.imageFile,
  });

  factory TourGuideFormState.initial() {
    return const TourGuideFormState(
      name: '',
      nickName: '',
      description: '',
      experience: '',
      selectedRegionIds: <int>[],
      selectedLanguageIds: <int>[],
      socialLinks: <String, String>{},
      imageUrl: '',
      imageFile: null,
    );
  }

  TourGuideFormState copyWith({
    String? name,
    String? nickName,
    String? description,
    String? experience,
    List<int>? selectedRegionIds,
    List<int>? selectedLanguageIds,
    Map<String, String>? socialLinks,
    String? imageUrl,
    File? imageFile,

    bool resetImageFile = false,
  }) {
    return TourGuideFormState(
      name: name ?? this.name,
      nickName: nickName ?? this.nickName,
      description: description ?? this.description,
      experience: experience ?? this.experience,
      selectedRegionIds: selectedRegionIds ?? this.selectedRegionIds,
      selectedLanguageIds: selectedLanguageIds ?? this.selectedLanguageIds,
      socialLinks: socialLinks ?? this.socialLinks,
      imageUrl: imageUrl ?? this.imageUrl,
      imageFile: resetImageFile ? null : (imageFile ?? this.imageFile),
    );
  }

  Map<String, dynamic> toJson() => {
    'name': name,
    'nickName': nickName,
    'description': description,
    'experience': experience,
    'regions': selectedRegionIds,
    'languages': selectedLanguageIds,
    'socialLinks': socialLinks,
    'image': imageUrl,
  };

  factory TourGuideFormState.fromJson(Map<String, dynamic> json) {
    List<int> _toIntList(List<dynamic>? list) => (list ?? const [])
        .map((e) => e is int ? e : int.tryParse('$e') ?? -1)
        .where((e) => e >= 0)
        .toList();

    final rawSocial =
        (json['socialLinks'] as Map<String, dynamic>? ?? const {});
    final social = rawSocial.map((k, v) => MapEntry(k, (v ?? '').toString()));

    return TourGuideFormState(
      name: json['name']?.toString() ?? '',
      nickName: json['nickName']?.toString() ?? '',
      description: json['description']?.toString() ?? '',
      experience: json['experience']?.toString() ?? '',
      selectedRegionIds: _toIntList(json['regions'] as List<dynamic>?),
      selectedLanguageIds: _toIntList(json['languages'] as List<dynamic>?),
      socialLinks: social,
      imageUrl: json['image']?.toString() ?? '',
      imageFile: null,
    );
  }

  @override
  List<Object?> get props => [
    name,
    nickName,
    description,
    experience,
    selectedRegionIds,
    selectedLanguageIds,
    socialLinks,
    imageUrl,
    imageFile?.path, // 👈 قارن بالـ path بدل الـ File نفسه
  ];
}
