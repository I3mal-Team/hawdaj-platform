class AppValidators {
  // Generic validation patterns
  static const String emailPattern =
      r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$';
  static const String arabicNamePattern = r'^[\u0600-\u06FF\s]+$';
  static const String englishNamePattern = r'^[a-zA-Z\s]+$';
  static const String mixedNamePattern = r'^[\u0600-\u06FF\sa-zA-Z]+$';

  /// Validates email format
  static String? validateEmail(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'البريد الإلكتروني مطلوب';
    }

    final trimmed = value.trim();
    if (!RegExp(emailPattern).hasMatch(trimmed)) {
      return 'يرجى إدخال بريد إلكتروني صحيح';
    }

    return null;
  }

  /// Validates required field
  static String? validateRequired(String? value, {String? fieldName}) {
    if (value == null || value.trim().isEmpty) {
      return '${fieldName ?? 'هذا الحقل'} مطلوب';
    }
    return null;
  }

  /// Validates name (Arabic, English, or mixed)
  static String? validateName(String? value, {String? fieldName}) {
    if (value == null || value.trim().isEmpty) {
      return '${fieldName ?? 'الاسم'} مطلوب';
    }

    final trimmed = value.trim();
    if (trimmed.length < 2) {
      return '${fieldName ?? 'الاسم'} يجب أن يكون أكثر من حرف واحد';
    }

    if (trimmed.length > 50) {
      return '${fieldName ?? 'الاسم'} لا يمكن أن يزيد عن 50 حرف';
    }

    // Allow Arabic, English, and spaces
    if (!RegExp(mixedNamePattern).hasMatch(trimmed)) {
      return '${fieldName ?? 'الاسم'} يجب أن يحتوي على أحرف صحيحة فقط';
    }

    return null;
  }

  /// Validates password
  static String? validatePassword(String? value) {
    if (value == null || value.isEmpty) {
      return 'كلمة المرور مطلوبة';
    }

    if (value.length < 8) {
      return 'كلمة المرور يجب أن تكون على الأقل 8 أحرف';
    }

    if (value.length > 50) {
      return 'كلمة المرور لا يمكن أن تزيد عن 50 حرف';
    }

    // At least one uppercase letter
    if (!RegExp(r'[A-Z]').hasMatch(value)) {
      return 'كلمة المرور يجب أن تحتوي على حرف كبير واحد على الأقل';
    }

    // At least one lowercase letter
    if (!RegExp(r'[a-z]').hasMatch(value)) {
      return 'كلمة المرور يجب أن تحتوي على حرف صغير واحد على الأقل';
    }

    // At least one digit
    if (!RegExp(r'[0-9]').hasMatch(value)) {
      return 'كلمة المرور يجب أن تحتوي على رقم واحد على الأقل';
    }

    return null;
  }

  /// Validates phone number (international format)
  static String? validatePhone(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'رقم الهاتف مطلوب';
    }

    final trimmed = value.trim().replaceAll(' ', '').replaceAll('-', '');

    // Basic phone validation (can be customized based on requirements)
    if (!RegExp(r'^\+?[1-9]\d{1,14}$').hasMatch(trimmed)) {
      return 'يرجى إدخال رقم هاتف صحيح';
    }

    return null;
  }

  /// Validates confirm password
  static String? validateConfirmPassword(
    String? value,
    String? originalPassword,
  ) {
    final passwordError = validatePassword(value);
    if (passwordError != null) return passwordError;

    if (value != originalPassword) {
      return 'كلمات المرور غير متطابقة';
    }

    return null;
  }

  /// Validates minimum length
  static String? validateMinLength(
    String? value,
    int minLength, {
    String? fieldName,
  }) {
    if (value == null || value.trim().isEmpty) {
      return '${fieldName ?? 'هذا الحقل'} مطلوب';
    }

    if (value.trim().length < minLength) {
      return '${fieldName ?? 'هذا الحقل'} يجب أن يكون على الأقل $minLength أحرف';
    }

    return null;
  }

  /// Validates maximum length
  static String? validateMaxLength(
    String? value,
    int maxLength, {
    String? fieldName,
  }) {
    if (value != null && value.trim().length > maxLength) {
      return '${fieldName ?? 'هذا الحقل'} لا يمكن أن يزيد عن $maxLength حرف';
    }

    return null;
  }

  /// Combines multiple validators
  static String? Function(String?) combineValidators(
    List<String? Function(String?)> validators,
  ) {
    return (String? value) {
      for (final validator in validators) {
        final result = validator(value);
        if (result != null) return result;
      }
      return null;
    };
  }
}
