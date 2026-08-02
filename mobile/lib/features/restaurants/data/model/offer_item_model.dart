class OfferItemModel {
  final String image;
  final String from;
  final String to;
  final String title;
  final String? description;
  final int discount;
  final String price;
  final num priceAfterDiscount;

  OfferItemModel({
    required this.image,
    required this.from,
    required this.to,
    required this.title,
    this.description,
    required this.discount,
    required this.price,
    required this.priceAfterDiscount,
  });

  factory OfferItemModel.fromJson(Map<String, dynamic> json) {
    return OfferItemModel(
      image: json['image'] ?? '',
      from: json['from'] ?? '',
      to: json['to'] ?? '',
      title: json['title'] ?? '',
      description: json['description'],
      discount: json['discount'] ?? 0,
      price: json['price'] ?? '0.0',
      priceAfterDiscount: json['price_after_discount'] ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'image': image,
      'from': from,
      'to': to,
      'title': title,
      'description': description,
      'discount': discount,
      'price': price,
      'price_after_discount': priceAfterDiscount,
    };
  }
}
