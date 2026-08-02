class StoryModel {
  final int id;
  final String type;
  final String? text;
  final String file;
  final String status;
  final int totalViews;
  final int totalLikes;
  final int totalComments;
  final int totalShares;

  StoryModel({
    required this.id,
    required this.type,
    this.text,
    required this.file,
    required this.status,
    required this.totalViews,
    required this.totalLikes,
    required this.totalComments,
    required this.totalShares,
  });

  factory StoryModel.fromJson(Map<String, dynamic> json) {
    return StoryModel(
      id: json['id'] ?? 0,
      type: json['type'] ?? '',
      text: json['text'] == "null" ? null : json['text'],
      file: json['file'] ?? '',
      status: json['status'] ?? '',
      totalViews: json['total_views'] ?? 0,
      totalLikes: json['total_likes'] ?? 0,
      totalComments: json['total_comments'] ?? 0,
      totalShares: json['total_shares'] ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'type': type,
      'text': text,
      'file': file,
      'status': status,
      'total_views': totalViews,
      'total_likes': totalLikes,
      'total_comments': totalComments,
      'total_shares': totalShares,
    };
  }
}
