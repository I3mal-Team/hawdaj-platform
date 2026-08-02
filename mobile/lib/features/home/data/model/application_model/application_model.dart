class ApplicationModel {
  final int id;
  final String slug;
  final String type;
  final String? link;
  final String? iosLink;
  final String? androidLink;
  final String image;
  final String title;
  final String description;
  final bool active;
  final bool showInHome;
  final List<AppCategory> categories;

  ApplicationModel({
    required this.id,
    required this.slug,
    required this.type,
    this.link,
    this.iosLink,
    this.androidLink,
    required this.image,
    required this.title,
    required this.description,
    required this.active,
    required this.showInHome,
    required this.categories,
  });

  factory ApplicationModel.fromJson(Map<String, dynamic> json) {
    return ApplicationModel(
      id: (json['id'] as num?)?.toInt() ?? 0,
      slug: json['slug'] as String? ?? '',
      type: json['type'] as String? ?? '',
      link: json['link'] as String?,
      iosLink: json['ios_link'] as String?,
      androidLink: json['android_link'] as String?,
      image: json['image'] as String? ?? '',
      title: json['title'] as String? ?? '',
      description: json['description'] as String? ?? '',
      active: json['active'] as bool? ?? false,
      showInHome: json['show_in_home'] as bool? ?? false,
      categories: (json['categories'] as List<dynamic>? ?? [])
          .map((e) => AppCategory.fromJson(e as Map<String, dynamic>))
          .toList(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'slug': slug,
      'type': type,
      'link': link,
      'ios_link': iosLink,
      'android_link': androidLink,
      'image': image,
      'title': title,
      'description': description,
      'active': active,
      'show_in_home': showInHome,
      'categories': categories.map((e) => e.toJson()).toList(),
    };
  }

  ApplicationModel copyWith({
    int? id,
    String? slug,
    String? type,
    String? link,
    String? iosLink,
    String? androidLink,
    String? image,
    String? title,
    String? description,
    bool? active,
    bool? showInHome,
    List<AppCategory>? categories,
  }) {
    return ApplicationModel(
      id: id ?? this.id,
      slug: slug ?? this.slug,
      type: type ?? this.type,
      link: link ?? this.link,
      iosLink: iosLink ?? this.iosLink,
      androidLink: androidLink ?? this.androidLink,
      image: image ?? this.image,
      title: title ?? this.title,
      description: description ?? this.description,
      active: active ?? this.active,
      showInHome: showInHome ?? this.showInHome,
      categories: categories ?? this.categories,
    );
  }
}

class AppCategory {
  final int id;
  final String icon;
  final String name;

  AppCategory({required this.id, required this.icon, required this.name});

  factory AppCategory.fromJson(Map<String, dynamic> json) {
    return AppCategory(
      id: (json['id'] as num?)?.toInt() ?? 0,
      icon: json['icon'] as String? ?? '',
      name: json['name'] as String? ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {'id': id, 'icon': icon, 'name': name};
  }
}
