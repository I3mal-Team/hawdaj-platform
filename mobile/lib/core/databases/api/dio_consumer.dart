import 'dart:io';
import 'package:chucker_flutter/chucker_flutter.dart';
import 'package:dio/dio.dart';
import 'package:hawdaj/core/databases/api/location_interceptor.dart';
import 'package:hawdaj/core/databases/api/token_interceptor.dart';
import 'package:hawdaj/core/databases/language_interceptor.dart';
import 'package:hawdaj/core/utils/app_environment_manager.dart';
import 'package:pretty_dio_logger/pretty_dio_logger.dart';
import 'api_consumer.dart';
import 'end_points.dart';

class DioConsumer extends ApiConsumer {
  final Dio dio;

  DioConsumer({required this.dio}) {
    dio.options.baseUrl = EndPoints.baseUrl;
    dio.options.headers['Accept'] = 'application/json';
    dio.options.headers['Content-Type'] = 'application/json';
    // dio.options.headers['accept-language'] = 'ar';
    // dio.options.headers['locale'] = 'ar';
    dio.options.headers['accept-tokenapi'] =
        'k*RQ=z*2K4@WnA6d2h_&z39?bE9kDszkwh4XTePpU_vA';
    dio.options.headers['User-Agent'] =
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36';
    dio.options.followRedirects = false;
    dio.interceptors.addAll([
      LanguageInterceptor(),
      //   LocationInterceptor(),
      const TokenInterceptor(),

      if (AppEnvironmentManager().isChuckerEnabled) ChuckerDioInterceptor(),
      PrettyDioLogger(
        requestBody: true,
        responseBody: true,
        enabled: true,
        requestHeader: true,
        request: true,
      ),
    ]);
  }

  //!POST
  @override
  Future post(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    bool isFormData = false,
  }) async {
    try {
      if (isFormData) {
        // Create a new Dio instance specifically for form data to avoid header conflicts
        final formDio = Dio();
        formDio.options.baseUrl = dio.options.baseUrl;

        // Set only the required headers for form data (no Content-Type)
        formDio.options.headers = {
          'Accept': 'application/json, text/plain, */*',
          'accept-language': 'ar',
          'locale': 'ar',
          'accept-tokenapi': 'k*RQ=z*2K4@WnA6d2h_&z39?bE9kDszkwh4XTePpU_vA',
          'User-Agent':
              'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
          'Sec-Fetch-Site': 'same-site',
          'Sec-Fetch-Mode': 'cors',
          'Sec-Fetch-Dest': 'empty',
        };

        // Add interceptors for debugging
        formDio.interceptors.addAll([
          TokenInterceptor(),
          PrettyDioLogger(
            requestBody: true,
            responseBody: true,
            enabled: true,
            requestHeader: true,
            request: true,
          ),
        ]);

        final response = await formDio.post(
          path,
          data: data is FormData ? data : FormData.fromMap(data),
          queryParameters: queryParameters,
        );

        return response.data;
      } else {
        // Use regular dio for non-form data
        final response = await dio.post(
          path,
          data: data,
          queryParameters: queryParameters,
        );
        return response.data;
      }
    } catch (e) {
      print('DioConsumer POST error: $e');
      rethrow;
    }
  }

  //!GET
  @override
  Future get(
    String path, {
    Object? data,
    Map<String, dynamic>? queryParameters,
  }) async {
    // dio.interceptors.add(TokenInterceptor());
    var res = await dio.get(path, data: data, queryParameters: queryParameters);
    return res.data;
  }

  //!DELETE
  @override
  Future delete(
    String path, {
    Object? data,
    Map<String, dynamic>? queryParameters,
  }) async {
    var res = await dio.delete(
      path,
      data: data,
      queryParameters: queryParameters,
    );
    return res.data;
  }

  //!PATCH
  @override
  Future patch(
    String path, {
    dynamic data,
    Map<String, dynamic>? queryParameters,
    bool isFormData = true,
  }) async {
    var res = await dio.patch(
      path,
      data: isFormData ? FormData.fromMap(data) : data,
      queryParameters: queryParameters,
    );
    return res.data;
  }

  @override
  Future put(
    String path, {
    data,
    Map<String, dynamic>? queryParameters,
    bool isFormData = true,
  }) async {
    var response = await dio.put(
      path,
      data: isFormData ? FormData.fromMap(data) : data,
      queryParameters: queryParameters,
    );
    return response.data;
  }

  @override
  Future<File> downloadFile(
    String url, {
    required String fileName,
    String method = 'GET',
  }) async {
    final tempDir = Directory.systemTemp; // أو أي مسار مؤقت
    final savePath = '${tempDir.path}/$fileName';

    try {
      if (method == 'POST') {
        final response = await dio.post(
          url,
          options: Options(responseType: ResponseType.bytes),
        );

        final file = File(savePath);
        await file.writeAsBytes(response.data);
        return file;
      } else {
        await dio.download(
          url,
          savePath,
          options: Options(responseType: ResponseType.bytes),
        );
        return File(savePath);
      }
    } catch (e) {
      rethrow; // علشان يتم التقاط الخطأ في repo ويُحوَّل إلى Failure
    }
  }

  void refreshBaseUrl() {
    dio.options.baseUrl = EndPoints.baseUrl;
    // يمكنك أيضا إعادة تحميل أي إعدادات أخرى إذا لزم الأمر
  }

  // دالة لإضافة أو إزالة Chucker
  void refreshChuckerInterceptor() {
    dio.interceptors.removeWhere((i) => i is ChuckerDioInterceptor);
    if (AppEnvironmentManager().isChuckerEnabled) {
      dio.interceptors.add(ChuckerDioInterceptor());
    }
  }
}
