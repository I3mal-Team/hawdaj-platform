// import 'package:hawdaj/core/databases/api/dio_consumer.dart';
// import 'package:hawdaj/core/services/service_locator.dart';
// import 'package:hawdaj/core/utils/app_constants.dart';
// import 'package:hawdaj/core/utils/storage_service.dart';
// import 'package:flutter_bloc/flutter_bloc.dart';
// import 'package:flutter/material.dart' show Locale;
// import 'package:meta/meta.dart';

// part 'locale_state.dart';

// class LocaleCubit extends Cubit<LocaleState> {
//   LocaleCubit()
//     : super(const SelectedLocale(Locale(AppConstants.kArabicLanguageCode))) {
//     loadSavedLocale();
//   }

//   Future<void> loadSavedLocale() async {
//     final langCode = await StorageService.getString(AppConstants.kLanguageKey);
//     final locale = Locale(langCode ?? AppConstants.kArabicLanguageCode);
//     if (locale != state.locale) {
//       _updateLanguageHeader(locale.languageCode);
//       emit(SelectedLocale(locale));
//     }
//   }

//   Future<void> toArabic() async {
//     await StorageService.saveString(
//       AppConstants.kLanguageKey,
//       AppConstants.kArabicLanguageCode,
//     );
//     _updateLanguageHeader(AppConstants.kArabicLanguageCode);
//     emit(const SelectedLocale(Locale(AppConstants.kArabicLanguageCode)));
//   }

//   Future<void> toEnglish() async {
//     await StorageService.saveString(
//       AppConstants.kLanguageKey,
//       AppConstants.kEnglishLanguageCode,
//     );
//     _updateLanguageHeader(AppConstants.kEnglishLanguageCode);
//     emit(const SelectedLocale(Locale(AppConstants.kEnglishLanguageCode)));
//   }

//   Future<void> setLocale(Locale locale) async {
//     final langCode = locale.languageCode;
//     await StorageService.saveString(AppConstants.kLanguageKey, langCode);
//     _updateLanguageHeader(langCode);
//     emit(SelectedLocale(locale));
//   }

//   bool isArabic() {
//     return state.locale.languageCode == AppConstants.kArabicLanguageCode;
//   }

//   void _updateLanguageHeader(String langCode) {
//     final dio = getIt<DioConsumer>().dio;
//     dio.options.headers['accept-language'] = langCode;
//   }
// }
