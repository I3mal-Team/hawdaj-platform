import 'dart:io';
import 'package:bloc/bloc.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:equatable/equatable.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';
import 'package:hawdaj/features/my_properties/data/model/create_property_request.dart';

part 'add_property_form_state.dart';

class AddPropertyFormCubit extends Cubit<AddPropertyFormState> {
  AddPropertyFormCubit() : super(const AddPropertyFormState());

  // ───────────────────────────────
  // 🔹 جميع الأنواع المتاحة
  // ───────────────────────────────
  static const List<String> propertyTypes = ['place', 'store', 'event', 'zad'];

  // ───────────────────────────────
  // 🏪 أنواع الاتصال للمتجر
  // ───────────────────────────────
  static const List<String> connectionTypes = ['online', 'local'];
  static const List<String> addressTypes = ['link', 'map'];

  static List<GlobalPopUpData> getConnectionTypesAsDropdownItems() {
    return connectionTypes.asMap().entries.map((entry) {
      return GlobalPopUpData(
        id: entry.key,
        title: entry.value == 'online' ? 'متجر إلكتروني' : 'متجر محلي',
      );
    }).toList();
  }

  static List<GlobalPopUpData> getAddressTypesAsDropdownItems() {
    return addressTypes.asMap().entries.map((entry) {
      return GlobalPopUpData(
        id: entry.key,
        title: entry.value == 'link' ? 'رابط' : 'خريطة',
      );
    }).toList();
  }

  static List<GlobalPopUpData> getPropertyTypesAsDropdownItems() {
    return propertyTypes.asMap().entries.map((entry) {
      return GlobalPopUpData(
        id: entry.key,
        title: _getLocalizedPropertyType(entry.value),
      );
    }).toList();
  }

  void updateRegionId(int? id) {
    emit(state.copyWith(selectedRegionId: id));
    _debugPrintState('updateRegionId');
  }

  void updateCityId(int? id) {
    emit(state.copyWith(selectedCityId: id));
    _debugPrintState('updateCityId');
  }

  static String _getLocalizedPropertyType(String type) {
    switch (type) {
      case 'place':
        return 'مكان';
      case 'store':
        return 'متجر';
      case 'event':
        return 'حدث';
      case 'zad':
        return 'زاد';
      default:
        return type;
    }
  }

  void updateLocation(double lat, double lng) {
    emit(state.copyWith(latitude: lat, longitude: lng));
    _debugPrintState('updateLocation');
  }

  // ───────────────────────────────
  // 🍃 جميع المواسم المتاحة
  // ───────────────────────────────
  static const List<String> seasonTypes = [
    'all_year',
    'spring',
    'summer',
    'fall',
    'winter',
  ];

  static List<GlobalPopUpData> getSeasonItems() {
    return seasonTypes.asMap().entries.map((entry) {
      return GlobalPopUpData(
        id: entry.key,
        title: getLocalizedSeason(entry.value),
      );
    }).toList();
  }

  static String getLocalizedSeason(String season) {
    switch (season) {
      case 'all_year':
        return 'season_all_year'.tr();
      case 'spring':
        return 'season_spring'.tr();
      case 'summer':
        return 'season_summer'.tr();
      case 'fall':
        return 'season_autumn'.tr();
      case 'winter':
        return 'season_winter'.tr();
      default:
        return season;
    }
  }

  // ───────────────────────────────
  // 🧩 تحديث الحقول العامة
  // ───────────────────────────────
  void updateTitle(String value) {
    _emitWithValidation(title: value.trim());
    _debugPrintState('updateTitle');
  }

  void updateDescription(String value) {
    _emitWithValidation(description: value.trim());
    _debugPrintState('updateDescription');
  }

  void updateAddress(String value) {
    _emitWithValidation(address: value.trim());
    _debugPrintState('updateAddress');
  }

  void updateOwnershipProof(File file) {
    _emitWithValidation(ownershipProofFile: file);
    _debugPrintState('updateOwnershipProof');
  }

  void updateImages(List<File> files) {
    _emitWithValidation(images: files);
    _debugPrintState('updateImages');
  }

  void selectType(String type) {
    _emitWithValidation(type: type);
    _debugPrintState('selectType');
  }

  void updateSelectedCategories(List<int> ids) {
    emit(state.copyWith(selectedCategoryIds: ids));
    _debugPrintState('updateSelectedCategories');
  }

  List<String> get availablePlatforms {
    final platforms = <String>[];

    if (state.whatsapp == null) platforms.add('WhatsApp');
    if (state.instagramLink == null) platforms.add('Instagram');
    if (state.websiteLink == null) platforms.add('Personal Site');

    return platforms;
  }

  // ───────────────────────────────
  // ⚙️ تحديث الحقول الخاصة بكل نوع
  // ───────────────────────────────

  // place
  void updateSeasons(List<String> values) {
    emit(state.copyWith(seasons: values));
    _debugPrintState('updateSeasons');
  }

  void updatePriceId(int id) {
    emit(state.copyWith(priceId: id));
    _debugPrintState('updatePriceId');
  }

  void addSocialLink(String platform, String link) {
    switch (platform) {
      case 'WhatsApp':
        updateWhatsapp(link);
        break;
      case 'Instagram':
        updateInstagram(link);
        break;
      case 'Personal Site':
        updateWebsite(link);
        break;
    }
  }

  // event
  void updateTicketLink(String link) {
    emit(state.copyWith(ticketLink: link.trim()));
    _debugPrintState('updateTicketLink');
  }

  void updateVideoUrl(String url) {
    emit(state.copyWith(videoUrl: url.trim()));
    _debugPrintState('updateVideoUrl');
  }

  void updateDates(String from, String to) {
    emit(state.copyWith(dateFrom: from, dateTo: to));
    _debugPrintState('updateDates');
  }

  void updateDateFrom(DateTime? date) {
    final iso = date?.toIso8601String();
    final text = date != null ? DateFormat('yyyy-MM-dd').format(date) : null;
    emit(state.copyWith(dateFrom: iso, dateFromText: text));
    _debugPrintState('updateDateFrom');
  }

  void updateDateTo(DateTime? date) {
    final iso = date?.toIso8601String();
    final text = date != null ? DateFormat('yyyy-MM-dd').format(date) : null;
    emit(state.copyWith(dateTo: iso, dateToText: text));
    _debugPrintState('updateDateTo');
  }

  // zad
  void updateMenuFiles(List<File> files) {
    emit(state.copyWith(menuFile: files));
    _debugPrintState('updateMenuFiles');
  }

  // zad
  void updateFoodCategories(List<int> ids) {
    emit(
      state.copyWith(
        // selectedCategoryIds: ids, // نفس المنطق المستخدم في الزاد
        foodCategories: ids.map((e) => e).toList(),
      ),
    );
    _debugPrintState('updateFoodCategories');
  }

  // ───────────────────────────────
  // 🌐 تحديث روابط التواصل
  // ───────────────────────────────
  List<Map<String, String>> get currentSocialLinks {
    final links = <Map<String, String>>[];

    if (state.whatsapp?.isNotEmpty ?? false) {
      links.add({'platform': 'WhatsApp', 'link': state.whatsapp!});
    }
    if (state.instagramLink?.isNotEmpty ?? false) {
      links.add({'platform': 'Instagram', 'link': state.instagramLink!});
    }
    if (state.websiteLink?.isNotEmpty ?? false) {
      links.add({'platform': 'Website', 'link': state.websiteLink!});
    }

    return links;
  }

  void removeSocialLink(String platform) {
    switch (platform) {
      case 'WhatsApp':
        updateWhatsapp(null);
        break;
      case 'Instagram':
        updateInstagram(null);
        break;
      case 'Website':
        updateWebsite(null);
        break;
    }
  }

  void updateWhatsapp(String? value) {
    emit(state.copyWith(whatsapp: value?.trim()));
    _debugPrintState('updateWhatsapp');
  }

  void updateFacebook(String? value) {
    emit(state.copyWith(facebookLink: value?.trim()));
    _debugPrintState('updateFacebook');
  }

  void updateInstagram(String? value) {
    emit(state.copyWith(instagramLink: value?.trim()));
    _debugPrintState('updateInstagram');
  }

  void updateWebsite(String? value) {
    emit(state.copyWith(websiteLink: value?.trim()));
    _debugPrintState('updateWebsite');
  }

  // ───────────────────────────────
  // 🏪 تحديث حقول المتجر
  // ───────────────────────────────
  void updateConType(String? value) {
    emit(state.copyWith(conType: value));
    _debugPrintState('updateConType');
  }

  void updateAddressType(String? value) {
    emit(state.copyWith(addressType: value));
    _debugPrintState('updateAddressType');
  }

  // ───────────────────────────────
  // 🔍 التحقق و التحديث العام
  // ───────────────────────────────
  void _emitWithValidation({
    String? title,
    String? description,
    String? address,
    File? ownershipProofFile,
    List<File>? images,
    String? type,
  }) {
    final newState = state.copyWith(
      title: title ?? state.title,
      description: description ?? state.description,
      address: address ?? state.address,
      ownershipProofFile: ownershipProofFile ?? state.ownershipProofFile,
      images: images ?? state.images,
      type: type ?? state.type,
    );

    final isValid = _validateAll(
      title: newState.title,
      description: newState.description,
      address: newState.address,
      ownershipProof: newState.ownershipProofFile,
      images: newState.images,
      type: newState.type,
    );

    emit(newState.copyWith(isValid: isValid));
  }

  bool _validateAll({
    String? title,
    String? description,
    String? address,
    File? ownershipProof,
    List<File>? images,
    String? type,
  }) {
    return (title?.isNotEmpty ?? false) &&
        (description?.isNotEmpty ?? false) &&
        (address?.isNotEmpty ?? false) &&
        ownershipProof != null &&
        (images?.isNotEmpty ?? false) &&
        (type?.isNotEmpty ?? false);
  }

  CreatePropertyRequest toRequest() {
    return CreatePropertyRequest(
      title: state.title ?? '',
      description: state.description ?? '',
      address: state.address ?? '',
      type: state.type ?? '',
      lat: (state.latitude == 0.0) ? null : state.latitude,
      long: (state.longitude == 0.0) ? null : state.longitude,
      regionId: (state.selectedRegionId == 0) ? null : state.selectedRegionId,
      cityId: (state.selectedCityId == 0) ? null : state.selectedCityId,
      ownershipProofFile: state.ownershipProofFile?.path,
      imagePaths: state.images?.map((e) => e.path).toList(),
      menuFile: state.menuFile?.map((e) => e.path).toList(),
      whatsapp: state.whatsapp,
      facebookLink: state.facebookLink,
      instagramLink: state.instagramLink,
      websiteLink: state.websiteLink,

      categories: state.type == 'zad'
          ? state
                .selectedCategoryIds // ✔️ فئات عامة
          : state.selectedCategoryIds, // ✔️ طبيعية

      foodCategories: state.type == 'zad'
          ? state
                .foodCategories // ✔️ فئات أكل فقط لو ZAD
          : null,

      seasons: state.seasons,
      priceId: state.priceId,
      ticketLink: state.ticketLink,
      videoUrl: state.videoUrl,
      dateFrom: state.dateFromText,
      dateTo: state.dateToText,

      // store fields
      conType: state.conType,
      addressType: state.addressType,
    );
  }

  // CreatePropertyRequest toRequest() {
  //   return CreatePropertyRequest(
  //     title: state.title ?? '',
  //     description: state.description ?? '',
  //     address: state.address ?? '',
  //     type: state.type ?? '',
  //     lat: state.latitude ?? 0.0,
  //     long: state.longitude ?? 0.0,
  //     regionId: state.selectedRegionId ?? 0,
  //     cityId: state.selectedCityId ?? 0,
  //     ownershipProofFile: state.ownershipProofFile?.path,
  //     imagePaths: state.images?.map((e) => e.path).toList(),
  //     menuFile: state.menuFile?.map((e) => e.path).toList(),
  //     whatsapp: state.whatsapp,
  //     facebookLink: state.facebookLink,
  //     instagramLink: state.instagramLink,
  //     websiteLink: state.websiteLink,

  //     // ✅ تأكد من إرسال كل القيم بدون فلترة أو null
  //     categories: state.selectedCategoryIds?.toList(),
  //     seasons: state.seasons?.toList(),
  //     priceId: state.priceId,
  //     ticketLink: state.ticketLink,
  //     videoUrl: state.videoUrl,
  //     dateFrom: state.dateFromText,
  //     dateTo: state.dateToText,
  //     foodCategories: state.foodCategories?.toList(),
  //   );
  // }

  // ───────────────────────────────
  // 🚀 بناء الـ Request Body
  // ───────────────────────────────
  Map<String, dynamic> buildRequestBody() {
    final base = {
      'title': state.title,
      'description': state.description,
      'address': state.address,
      'type': state.type,
      'ownership_proof_file': state.ownershipProofFile?.path,
      'images': state.images?.map((e) => e.path).toList(),
    };
    if (state.selectedRegionId != null && state.selectedRegionId != 0) {
      base['region_id'] = state.selectedRegionId.toString();
    }
    if (state.selectedCityId != null && state.selectedCityId != 0) {
      base['city_id'] = state.selectedCityId.toString();
    }
    if (state.latitude != null && state.latitude != 0.0) {
      base['lat'] = state.latitude;
    }
    if (state.longitude != null && state.longitude != 0.0) {
      base['long'] = state.longitude;
    }

    // روابط التواصل
    if (state.whatsapp?.isNotEmpty ?? false) base['whatsapp'] = state.whatsapp;
    if (state.instagramLink?.isNotEmpty ?? false)
      base['instagram_link'] = state.instagramLink;
    if (state.websiteLink?.isNotEmpty ?? false)
      base['website_link'] = state.websiteLink;

    // حسب النوع
    switch (state.type) {
      case 'place':
        if (state.seasons?.isNotEmpty ?? false) {
          final seasonsList =
              base.putIfAbsent('seasons[]', () => <String>[]) as List<String>;
          seasonsList.addAll(state.seasons!);
        }

        if (state.priceId != null) {
          base['price_id'] = state.priceId.toString();
        }
        break;

      case 'event':
        if (state.ticketLink?.isNotEmpty ?? false) {
          base['ticket_link'] = state.ticketLink;
        }
        if (state.videoUrl?.isNotEmpty ?? false) {
          base['video_url'] = state.videoUrl;
        }
        if (state.dateFromText?.isNotEmpty ?? false) {
          base['date_from'] = state.dateFromText;
        }
        if (state.dateToText?.isNotEmpty ?? false) {
          base['date_to'] = state.dateToText; // ✅ تصحيح من dateFromText
        }
        break;

      case 'zad':
        if (state.menuFile != null) {
          base['menu_file'] = state.menuFile!.map((f) => f.path).toList();
        }
        if (state.foodCategories?.isNotEmpty ?? false) {
          final foodList =
              base.putIfAbsent('food_categories[]', () => <int>[]) as List<int>;
          foodList.addAll(state.foodCategories!);
        }
        break;

      case 'store':
        if (state.conType?.isNotEmpty ?? false) {
          base['con_type'] = state.conType;
        }
        if (state.addressType?.isNotEmpty ?? false) {
          base['address_type'] = state.addressType;
        }
        break;

      default:
        break;
    }

    // switch (state.type) {
    //   case 'place':
    //     if (state.seasons?.isNotEmpty ?? false)
    //       base['seasons[]'] = state.seasons;
    //     if (state.priceId != null) base['price_id'] = state.priceId.toString();
    //     break;
    //   case 'event':
    //     if (state.ticketLink?.isNotEmpty ?? false)
    //       base['ticket_link'] = state.ticketLink;
    //     if (state.videoUrl?.isNotEmpty ?? false)
    //       base['video_url'] = state.videoUrl;
    //     if (state.dateFromText?.isNotEmpty ?? false)
    //       base['date_from'] = state.dateFromText;
    //     if (state.dateToText?.isNotEmpty ?? false)
    //       base['date_to'] = state.dateFromText;
    //     break;
    //   case 'zad':
    //     if (state.menuFile != null)
    //       base['menu_file'] = state.menuFile!.map((f) => f.path).toList();
    //     if (state.foodCategories?.isNotEmpty ?? false)
    //       base['food_categories[]'] = state.foodCategories;
    //     break;
    //   default:
    //     break;
    // }

    return base;
  }

  // ───────────────────────────────
  // 🧾 Submit
  // ───────────────────────────────
  void submit() {
    print('──────────────────────────────');
    print('✅ FORM SUBMISSION DATA');
    print('──────────────────────────────');
    print('• Title: ${state.title}');
    print('• Description: ${state.description}');
    print('• Address: ${state.address}');
    print('• Type: ${state.type}');
    print('• Seasons: ${state.seasons}');
    print('• Region ID: ${state.selectedRegionId}');
    print('• City ID: ${state.selectedCityId}');
    print('• Latitude: ${state.latitude}');
    print('• Longitude: ${state.longitude}');
    print('• Categories: ${state.selectedCategoryIds}');
    print('• Price ID: ${state.priceId}');
    print('• Ownership Proof File: ${state.ownershipProofFile?.path}');
    print('• Images: ${state.images?.map((e) => e.path).toList()}');
    print('• Ticket Link: ${state.ticketLink}');
    print('• Video URL: ${state.videoUrl}');
    print('• Date From: ${state.dateFromText}');
    print('• Date To: ${state.dateToText}');
    print('• Menu File: ${state.menuFile?.map((f) => f.path).toList()}');
    print('• Food Categories: ${state.foodCategories}');
    print('• WhatsApp: ${state.whatsapp}');
    print('• Facebook: ${state.facebookLink}');
    print('• Instagram: ${state.instagramLink}');
    print('• Website: ${state.websiteLink}');
    print('• Connection Type: ${state.conType}');
    print('• Address Type: ${state.addressType}');
    print('──────────────────────────────');
    print('📦 Final Request Body:');
    print(buildRequestBody());
    print('──────────────────────────────');
  }

  // ───────────────────────────────
  // 🧭 Debug Print (لكل تحديث)
  // ───────────────────────────────
  void _debugPrintState(String action) {
    print('───────────── $action ─────────────');
    print('Title: ${state.title}');
    print('Description: ${state.description}');
    print('Address: ${state.address}');
    print('Type: ${state.type}');
    print('Seasons: ${state.seasons}');
    print('Is Valid: ${state.isValid}');
    print('Selected Region ID: ${state.selectedRegionId}');
    print('Selected City ID: ${state.selectedCityId}');
    print('Price ID: ${state.priceId}');
    print('Images: ${state.images?.length}');

    print('──────────────────────────────────');
  }
}
