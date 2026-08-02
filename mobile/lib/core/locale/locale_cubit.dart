import 'package:bloc/bloc.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';
import 'package:hawdaj/core/utils/storage_service.dart';

part 'locale_state.dart';

class LocaleCubit extends Cubit<LocaleState> {
  LocaleCubit() : super(const LocaleInitial());

  static const String _languageKey = 'selected_language';

  /// Load saved language on app start
  Future<void> loadSavedLanguage() async {
    emit(const LocaleLoading());
    try {
      // ✅ Using getString instead of getData
      final savedLanguageCode = StorageService.getString(_languageKey);

      if (savedLanguageCode != null && savedLanguageCode.isNotEmpty) {
        final locale = Locale(savedLanguageCode);
        emit(LocaleLoaded(locale));
      } else {
        // If no saved language, use Arabic as default
        emit(const LocaleLoaded(Locale('ar')));
      }
    } catch (e) {
      // In case of error, use English as fallback
      emit(const LocaleLoaded(Locale('en')));
    }
  }

  /// Change language and save it
  Future<void> changeLanguage(BuildContext context, Locale locale) async {
    emit(const LocaleLoading());
    try {
      // Change language in easy_localization
      await context.setLocale(locale);

      // ✅ Using saveString instead of setData
      await StorageService.saveString(_languageKey, locale.languageCode);

      // Update state
      emit(LocaleLoaded(locale));
    } catch (e) {
      emit(LocaleError('فشل في تغيير اللغة: ${e.toString()}'));
    }
  }

  /// Get current locale
  Locale get currentLocale {
    if (state is LocaleLoaded) {
      return (state as LocaleLoaded).locale;
    }
    return const Locale('ar'); // Default
  }

  /// Check if current language is RTL
  bool get isRTL {
    return currentLocale.languageCode == 'ar';
  }

  /// Supported locales list
  static const List<Locale> supportedLocales = [
    Locale('ar'),
    Locale('en'),
    Locale('ru'),
    Locale('zh'),
  ];

  /// Language names for display
  static const Map<String, String> languageNames = {
    'ar': 'العربية',
    'en': 'English',
    'ru': 'Русский',
    'zh': '中文',
  };
}
