import 'package:dio/dio.dart';
import 'package:hawdaj/core/utils/auth_manager.dart';

class TokenInterceptor extends Interceptor {
  const TokenInterceptor();

  @override
  void onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    try {
      // شيل أي هيدر مخصص مش محتاجه (لو كان متضاف سابقًا)
      options.headers.remove('accept-tokenapi');

      // جيب التوكين من التخزين الآمن
      final token = await AuthManager.getToken();
      if (token != null && token.isNotEmpty) {
        options.headers['Authorization'] = 'Bearer $token';
      }

      // لو البيانات FormData سيب Dio يحدد Content-Type (فيه boundary)
      if (options.data is FormData) {
        options.headers.remove('Content-Type');
        options.contentType = Headers.multipartFormDataContentType;
      }
    } catch (e) {
      // هنا بس نسجل لو حابب، ما نرميش خطأ
    }
    handler.next(options);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    if (err.response?.statusCode == 401) {
      // ممكن تعمل Logout أو توجيه لصفحة تسجيل الدخول أو عرض توست
      // AuthManager.logout();
      // showToast('برجاء تسجيل الدخول أولاً');
    }
    handler.next(err);
  }
}
