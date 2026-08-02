part of 'edit_profile_cubit.dart';

// Sentinel value to distinguish between "not provided" and "explicitly set to null"
const _undefined = Object();

class EditProfileState {
  final String firstName;
  final String lastName;
  final String email;
  final String? phone;
  final String? gender;
  final bool isLoading;
  final bool isSuccess;
  final String? errorMessage;
  final UserModel? updatedUser;
  final bool isInitialized;
  final File? imageFile;
  final String? imageUrl;
  final Map<String, String> socialLinks;

  const EditProfileState({
    this.firstName = '',
    this.lastName = '',
    this.email = '',
    this.phone,
    this.gender,
    this.isLoading = false,
    this.isSuccess = false,
    this.errorMessage,
    this.updatedUser,
    this.isInitialized = false,
    this.imageFile,
    this.imageUrl,
    this.socialLinks = const {},
  });

  EditProfileState copyWith({
    String? firstName,
    String? lastName,
    String? email,
    Object? phone = _undefined,
    Object? gender = _undefined,
    bool? isLoading,
    bool? isSuccess,
    Object? errorMessage = _undefined,
    Object? updatedUser = _undefined,
    bool? isInitialized,
    Object? imageFile = _undefined,
    Object? imageUrl = _undefined,
    Map<String, String>? socialLinks,
  }) {
    return EditProfileState(
      firstName: firstName ?? this.firstName,
      lastName: lastName ?? this.lastName,
      email: email ?? this.email,
      phone: phone == _undefined ? this.phone : phone as String?,
      gender: gender == _undefined ? this.gender : gender as String?,
      isLoading: isLoading ?? this.isLoading,
      isSuccess: isSuccess ?? this.isSuccess,
      errorMessage: errorMessage == _undefined
          ? this.errorMessage
          : errorMessage as String?,
      updatedUser: updatedUser == _undefined
          ? this.updatedUser
          : updatedUser as UserModel?,
      isInitialized: isInitialized ?? this.isInitialized,
      imageFile: imageFile == _undefined ? this.imageFile : imageFile as File?,
      imageUrl: imageUrl == _undefined ? this.imageUrl : imageUrl as String?,
      socialLinks: socialLinks ?? this.socialLinks,
    );
  }

  bool get isFormValid =>
      firstName.isNotEmpty && lastName.isNotEmpty && email.isNotEmpty;

  String get genderDisplayText {
    switch (gender) {
      case 'male':
        return 'ذكر';
      case 'female':
        return 'أنثى';
      default:
        return 'اختر النوع';
    }
  }
}
