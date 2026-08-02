import '../utils/validators.dart';

/// Extended validation utilities for common use cases
class ValidationHelpers {
  /// Validates first name specifically
  static String? validateFirstName(String? value) {
    return AppValidators.validateName(value, fieldName: 'الاسم الأول');
  }

  /// Validates last name specifically
  static String? validateLastName(String? value) {
    return AppValidators.validateName(value, fieldName: 'الاسم الأخير');
  }

  /// Validates full name (combined first and last)
  static String? validateFullName(String? value) {
    return AppValidators.validateName(value, fieldName: 'الاسم الكامل');
  }

  /// Validates registration form fields
  static Map<String, String?> validateRegistrationForm({
    required String? firstName,
    required String? lastName,
    required String? email,
    required String? password,
  }) {
    return {
      'firstName': validateFirstName(firstName),
      'lastName': validateLastName(lastName),
      'email': AppValidators.validateEmail(email),
      'password': AppValidators.validatePassword(password),
    };
  }

  /// Checks if registration form is valid
  static bool isRegistrationFormValid({
    required String? firstName,
    required String? lastName,
    required String? email,
    required String? password,
  }) {
    final errors = validateRegistrationForm(
      firstName: firstName,
      lastName: lastName,
      email: email,
      password: password,
    );

    return !errors.values.any((error) => error != null);
  }

  /// Sanitizes input by trimming whitespace
  static String sanitizeInput(String? input) {
    return input?.trim() ?? '';
  }

  /// Sanitizes all registration inputs
  static Map<String, String> sanitizeRegistrationInputs({
    required String? firstName,
    required String? lastName,
    required String? email,
    required String? password,
  }) {
    return {
      'firstName': sanitizeInput(firstName),
      'lastName': sanitizeInput(lastName),
      'email': sanitizeInput(email),
      'password':
          password ?? '', // Don't trim password as spaces might be intentional
    };
  }
}
