import 'package:dio/dio.dart';
import 'package:path_provider/path_provider.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:open_filex/open_filex.dart';

Future<void> downloadFile(String url, {String? fileName}) async {
  try {
    // طلب الإذن
    final status = await Permission.storage.request();
    if (!status.isGranted) {
      throw Exception("Storage permission denied");
    }

    // تحديد مسار الحفظ
    final dir = await getApplicationDocumentsDirectory();
    final savePath = "${dir.path}/${fileName ?? url.split('/').last}";

    // تحميل الملف
    final dio = Dio();
    await dio.download(url, savePath);

    // فتح الملف بعد التحميل (اختياري)
    await OpenFilex.open(savePath);
  } catch (e) {
    print("Download error: $e");
    rethrow;
  }
}
