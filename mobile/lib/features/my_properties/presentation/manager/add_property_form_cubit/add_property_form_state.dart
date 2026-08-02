part of 'add_property_form_cubit.dart';

class AddPropertyFormState extends Equatable {
  // 🧩 الحقول العامة
  final String? title;
  final String? description;
  final String? address;
  final String? type;
  final File? ownershipProofFile;
  final List<File>? images;
  final bool isValid;
  final int? selectedRegionId;
  final int? selectedCityId;
  final List<int>? selectedCategoryIds;
  final double? latitude;
  final double? longitude;

  // 🌿 الحقول الخاصة بـ "place"
  final List<String>? seasons;
  final int? priceId;

  // 🎫 event
  final String? ticketLink;
  final String? videoUrl;
  final String? dateFrom;
  final String? dateTo;
  final String? dateFromText;
  final String? dateToText;

  // 🍴 zad
  final List<File>? menuFile;
  final List<int>? foodCategories;

  // 🏪 store
  final String? conType; // online or local
  final String? addressType; // link or map

  // 🔗 روابط التواصل
  final String? whatsapp;
  final String? facebookLink;
  final String? instagramLink;
  final String? websiteLink;

  const AddPropertyFormState({
    this.title,
    this.description,
    this.address,
    this.type,
    this.ownershipProofFile,
    this.images,
    this.isValid = false,
    this.selectedRegionId,
    this.dateFromText,
    this.dateToText,
    this.selectedCityId,
    this.selectedCategoryIds,
    this.latitude,
    this.longitude,
    this.seasons,
    this.priceId,
    this.ticketLink,
    this.videoUrl,
    this.dateFrom,
    this.dateTo,
    this.menuFile,
    this.foodCategories,
    this.conType,
    this.addressType,
    this.whatsapp,
    this.facebookLink,
    this.instagramLink,
    this.websiteLink,
  });

  AddPropertyFormState copyWith({
    String? title,
    String? description,
    String? address,
    String? type,
    File? ownershipProofFile,
    List<File>? images,
    bool? isValid,
    int? selectedRegionId,
    int? selectedCityId,
    List<int>? selectedCategoryIds,
    double? latitude,
    double? longitude,
    List<String>? seasons,
    int? priceId,
    String? ticketLink,
    String? videoUrl,
    String? dateFrom,
    String? dateTo,
    final List<File>? menuFile,
    List<int>? foodCategories,
    String? conType,
    String? addressType,
    Object? whatsapp = _noChange,
    Object? facebookLink = _noChange,
    Object? instagramLink = _noChange,
    Object? websiteLink = _noChange,

    String? dateFromText,
    String? dateToText,
  }) {
    return AddPropertyFormState(
      title: title ?? this.title,
      description: description ?? this.description,
      address: address ?? this.address,
      type: type ?? this.type,
      ownershipProofFile: ownershipProofFile ?? this.ownershipProofFile,
      images: images ?? this.images,
      isValid: isValid ?? this.isValid,
      selectedRegionId: selectedRegionId ?? this.selectedRegionId,
      selectedCityId: selectedCityId ?? this.selectedCityId,
      selectedCategoryIds: selectedCategoryIds ?? this.selectedCategoryIds,
      latitude: latitude ?? this.latitude,
      longitude: longitude ?? this.longitude,
      seasons: seasons ?? this.seasons,
      priceId: priceId ?? this.priceId,

      ticketLink: ticketLink ?? this.ticketLink,
      videoUrl: videoUrl ?? this.videoUrl,
      dateFrom: dateFrom ?? this.dateFrom,
      dateTo: dateTo ?? this.dateTo,
      menuFile: menuFile ?? this.menuFile,
      foodCategories: foodCategories ?? this.foodCategories,
      conType: conType ?? this.conType,
      addressType: addressType ?? this.addressType,

      whatsapp: whatsapp == _noChange ? this.whatsapp : whatsapp as String?,
      facebookLink: facebookLink == _noChange
          ? this.facebookLink
          : facebookLink as String?,
      instagramLink: instagramLink == _noChange
          ? this.instagramLink
          : instagramLink as String?,
      websiteLink: websiteLink == _noChange
          ? this.websiteLink
          : websiteLink as String?,
      dateFromText: dateFromText ?? this.dateFromText,
      dateToText: dateToText ?? this.dateToText,
    );
  }

  // معرف ثابت خاص داخل نفس الملف
  static const _noChange = Object();

  @override
  List<Object?> get props => [
    title,
    description,
    address,
    type,
    ownershipProofFile,
    images,
    isValid,
    selectedRegionId,
    selectedCityId,
    selectedCategoryIds,
    latitude,
    longitude,
    seasons,
    priceId,
    ticketLink,
    videoUrl,
    dateFrom,
    dateTo,
    menuFile,
    foodCategories,
    conType,
    addressType,
    whatsapp,
    facebookLink,
    instagramLink,
    websiteLink,
    dateFromText,
    dateToText,
  ];
}
