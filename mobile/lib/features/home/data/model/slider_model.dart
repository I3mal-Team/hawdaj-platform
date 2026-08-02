class SliderModel {
  final int id;
  final String? title;
  final int? orderId;
  final String image;
  final String? imageSmall;
  final String? imageMedium;
  final String? link;

  SliderModel({
    required this.id,
    this.title,
    this.orderId,
    required this.image,
    this.imageSmall,
    this.imageMedium,
    this.link,
  });

  factory SliderModel.fromJson(Map<String, dynamic> json) {
    return SliderModel(
      id: (json['id'] as num?)?.toInt() ?? 0,
      title: json['title'] as String?,
      orderId: (json['order_id'] as num?)?.toInt(),
      image: json['image'] as String? ?? '',
      imageSmall: json['image_small'] as String?,
      imageMedium: json['image_medium'] as String?,
      link: json['link'] as String?,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'order_id': orderId,
      'image': image,
      'image_small': imageSmall,
      'image_medium': imageMedium,
      'link': link,
    };
  }
}
