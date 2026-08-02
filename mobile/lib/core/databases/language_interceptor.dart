// language_interceptor.dart
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

class LanguageInterceptor extends Interceptor {
  @override
  void onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    try {
      // ✅ قراءة اللغة من SharedPreferences مباشرة
      final prefs = await SharedPreferences.getInstance();
      final savedLanguage = prefs.getString('selected_language') ?? 'ar';

      options.headers['accept-language'] = savedLanguage;
      options.headers['locale'] = savedLanguage;
    } catch (e) {
      options.headers['accept-language'] = 'ar';
      options.headers['locale'] = 'ar';
    }

    super.onRequest(options, handler);
  }
}
