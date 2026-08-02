// ignore_for_file: unused_shown_name

import 'dart:io';
import 'package:flutter/services.dart' show rootBundle, Uint8List;
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:path_provider/path_provider.dart';
import 'package:hawdaj/features/restaurants/data/model/menu_item_model.dart';
import 'package:http/http.dart' as http;

Future<void> downloadMenuAsPdf(List<MenuItemModel> items) async {
  final pdf = pw.Document();

  for (var item in items) {
    Uint8List? imageBytes;

    try {
      final url = "${item.image}";
      final response = await http.get(Uri.parse(url));
      if (response.statusCode == 200) {
        imageBytes = response.bodyBytes;
      }
    } catch (e) {
      print("Image download failed: $e");
    }

    pdf.addPage(
      pw.Page(
        build: (pw.Context context) => pw.Column(
          crossAxisAlignment: pw.CrossAxisAlignment.start,
          children: [
            if (imageBytes != null)
              pw.Image(pw.MemoryImage(imageBytes), height: 150),
            pw.Text("الاسم: ${item.title}", style: pw.TextStyle(fontSize: 16)),
            pw.Text("السعر: ${item.price} جنيه"),
            pw.Text("الوصف: ${item.description}"),
            pw.Divider(),
          ],
        ),
      ),
    );
  }

  final output = await getTemporaryDirectory();
  final file = File("${output.path}/menu.pdf");
  await file.writeAsBytes(await pdf.save());

  print("✅ Menu downloaded: ${file.path}");
}
