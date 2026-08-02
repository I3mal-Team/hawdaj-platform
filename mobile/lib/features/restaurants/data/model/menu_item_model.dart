class MenuItemModel {
  final String title;
  final String price;
  final String description;
  final String image;

  MenuItemModel({
    required this.title,
    required this.price,
    required this.description,
    required this.image,
  });

  factory MenuItemModel.fromJson(Map<String, dynamic> json) {
    return MenuItemModel(
      title: json['title'] ?? '',
      price: json['price'] ?? '',
      description: json['description'] ?? '',
      image: json['image'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'title': title,
      'price': price,
      'description': description,
      'image': image,
    };
  }
}
