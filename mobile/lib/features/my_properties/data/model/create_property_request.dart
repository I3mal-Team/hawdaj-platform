import 'package:dio/dio.dart';

class CreatePropertyRequest {
  // 🔹 الملفات (قد تكون صور أو إثبات ملكية أو منيو)
  final List<String>? imagePaths;
  final String? ownershipProofFile;
  final List<String>? menuFile;

  // 🔹 معلومات عامة
  final String type;
  final String title;
  final String description;

  // 🔹 الموقع
  final double? lat;
  final double? long;
  final String address;
  final int? regionId;
  final int? cityId;

  // 🔹 روابط التواصل
  final String? whatsapp;
  final String? facebookLink;
  final String? instagramLink;
  final String? websiteLink;

  // 🔹 بيانات إضافية حسب النوع
  final List<int>? categories;
  final List<String>? seasons;
  final int? priceId; // if type == 'place'
  final String? ticketLink; // if type == 'event'
  final String? videoUrl; // if type == 'event'
  final String? dateFrom; // if type == 'event'
  final String? dateTo; // if type == 'event'

  final List<int>? foodCategories; // if type == 'zad'

  // 🏪 بيانات المتجر
  final String? conType; // if type == 'store' (online/local)
  final String? addressType; // if type == 'store' (link/map)

  const CreatePropertyRequest({
    this.imagePaths,
    this.ownershipProofFile,
    this.menuFile,
    required this.type,
    required this.title,
    required this.description,
    this.lat,
    this.long,
    required this.address,
    this.regionId,
    this.cityId,
    this.whatsapp,
    this.facebookLink,
    this.instagramLink,
    this.websiteLink,
    this.categories,
    this.seasons,
    this.priceId,
    this.ticketLink,
    this.videoUrl,
    this.dateFrom,
    this.dateTo,
    this.foodCategories,
    this.conType,
    this.addressType,
  });

  /// تحويل البيانات إلى FormData
  ///
  Future<FormData> toFormData() async {
    final formData = FormData();

    // Helper لإضافة حقل واحد
    void addField(String key, dynamic value) {
      if (value != null && value.toString().isNotEmpty) {
        formData.fields.add(MapEntry(key, value.toString()));
      }
    }

    // 🔹 الحقول الأساسية
    addField('type', type);
    addField('title', title);
    addField('description', description);
    addField('lat', lat);
    addField('long', long);
    addField('address', address);
    addField('region_id', regionId);
    addField('city_id', cityId);
    addField('whatsapp', whatsapp);
    addField('facebook_link', facebookLink);
    addField('instagram_link', instagramLink);
    addField('website_link', websiteLink);
    addField('price_id', priceId);
    addField('ticket_link', ticketLink);
    addField('video_url', videoUrl);
    addField('date_from', dateFrom);
    addField('date_to', dateTo);
    addField('con_type', conType);
    addField('address_type', addressType);

    // 🔹 القوائم (تكرار فعلي لكل قيمة)
    if (categories != null) {
      for (final c in categories!) {
        formData.fields.add(MapEntry('categories[]', c.toString()));
      }
    }

    if (seasons != null) {
      for (final s in seasons!) {
        formData.fields.add(MapEntry('seasons[]', s));
      }
    }

    if (foodCategories != null) {
      for (final f in foodCategories!) {
        formData.fields.add(MapEntry('food_categories[]', f.toString()));
      }
    }

    // 🔹 الصور
    if (imagePaths != null && imagePaths!.isNotEmpty) {
      for (final path in imagePaths!) {
        formData.files.add(
          MapEntry(
            'image',
            await MultipartFile.fromFile(path, filename: path.split('/').last),
          ),
        );
      }
    }

    // 🔹 إثبات الملكية
    if (ownershipProofFile != null) {
      formData.files.add(
        MapEntry(
          'ownership_proof_file',
          await MultipartFile.fromFile(
            ownershipProofFile!,
            filename: ownershipProofFile!.split('/').last,
          ),
        ),
      );
    }

    // 🔹 ملفات المنيو
    if (menuFile != null && menuFile!.isNotEmpty) {
      for (final path in menuFile!) {
        formData.files.add(
          MapEntry(
            'menu_file',
            await MultipartFile.fromFile(path, filename: path.split('/').last),
          ),
        );
      }
    }

    print('✅ FormData fields count: ${formData.fields.length}');
    for (final f in formData.fields) {
      print('→ ${f.key}: ${f.value}');
    }

    return formData;
  }

  // Future<FormData> toFormData() async {
  //   final Map<String, dynamic> map = {
  //     'type': type,
  //     'title': title,
  //     'description': description,
  //     'lat': lat.toString(),
  //     'long': long.toString(),
  //     'address': address,
  //     'region_id': regionId.toString(),
  //     'city_id': cityId.toString(),
  //   };

  //   if (whatsapp != null) map['whatsapp'] = whatsapp;
  //   if (facebookLink != null) map['facebook_link'] = facebookLink;
  //   if (instagramLink != null) map['instagram_link'] = instagramLink;
  //   if (websiteLink != null) map['website_link'] = websiteLink;
  //   if (categories != null) {
  //     map['categories[]'] = categories!.map((e) => e.toString()).toList();
  //   }
  //   if (seasons != null) map['seasons[]'] = seasons;
  //   if (priceId != null) map['price_id'] = priceId.toString();
  //   if (ticketLink != null) map['ticket_link'] = ticketLink;
  //   if (videoUrl != null) map['video_url'] = videoUrl;
  //   if (dateFrom != null) map['date_from'] = dateFrom;
  //   if (dateTo != null) map['date_to'] = dateTo;
  //   if (foodCategories != null) {
  //     map['food_categories[]'] = foodCategories!
  //         .map((e) => e.toString())
  //         .toList();
  //   }

  //   // 🔹 الصور
  //   if (imagePaths != null && imagePaths!.isNotEmpty) {
  //     map['image'] = await Future.wait(
  //       imagePaths!.map(
  //         (path) =>
  //             MultipartFile.fromFile(path, filename: path.split('/').last),
  //       ),
  //     );
  //   }

  //   // 🔹 إثبات الملكية
  //   if (ownershipProofFile != null) {
  //     map['ownership_proof_file'] = await MultipartFile.fromFile(
  //       ownershipProofFile!,
  //       filename: ownershipProofFile!.split('/').last,
  //     );
  //   }

  //   // 🔹 ملفات المنيو
  //   if (menuFile != null && menuFile!.isNotEmpty) {
  //     map['menu_file'] = await Future.wait(
  //       menuFile!.map(
  //         (path) =>
  //             MultipartFile.fromFile(path, filename: path.split('/').last),
  //       ),
  //     );
  //   }

  //   return FormData.fromMap(map);
  // }
}
