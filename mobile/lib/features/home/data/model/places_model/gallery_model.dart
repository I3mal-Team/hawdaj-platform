// gallery_model.dart
class GalleryModel {
  final int id;
  final String file;
  final String type;
  final String mimeType;

  GalleryModel({
    required this.id,
    required this.file,
    required this.type,
    required this.mimeType,
  });

  factory GalleryModel.fromJson(Map<String, dynamic> json) {
    return GalleryModel(
      id: json['id'],
      file: json['file'],
      type: json['type'],
      mimeType: json['mime_type'],
    );
  }
}
