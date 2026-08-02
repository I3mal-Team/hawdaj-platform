// meta_model.dart
class MetaModel {
  final String title;
  final String description;
  final String? link;
  final dynamic keyWords;

  MetaModel({
    required this.title,
    required this.description,
    this.link,
    this.keyWords,
  });

  factory MetaModel.fromJson(Map<String, dynamic> json) {
    return MetaModel(
      title: json['title'],
      description: json['description'],
      link: json['link'],
      keyWords: json['keyWords'],
    );
  }
}
