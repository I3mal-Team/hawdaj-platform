class UserStoryModel {
  final int id;
  final String type;
  final String? text;
  final String? file;
  final String status;
  final int totalViews;
  final int totalLikes;
  final int totalComments;
  final int totalShares;

  UserStoryModel({
    required this.id,
    required this.type,
    this.text,
    this.file,
    required this.status,
    required this.totalViews,
    required this.totalLikes,
    required this.totalComments,
    required this.totalShares,
  });

  factory UserStoryModel.fromJson(Map<String, dynamic> json) {
    return UserStoryModel(
      id: json['id']?.toInt() ?? 0,
      type: json['type']?.toString() ?? '',
      text: json['text']?.toString(),
      file: json['file']?.toString(),
      status: json['status']?.toString() ?? '',
      totalViews: json['total_views']?.toInt() ?? 0,
      totalLikes: json['total_likes']?.toInt() ?? 0,
      totalComments: json['total_comments']?.toInt() ?? 0,
      totalShares: json['total_shares']?.toInt() ?? 0,
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

  @override
  String toString() {
    return 'UserStoryModel(id: $id, type: $type, text: $text, file: $file, status: $status, totalViews: $totalViews, totalLikes: $totalLikes, totalComments: $totalComments, totalShares: $totalShares)';
  }
}
