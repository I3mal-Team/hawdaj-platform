// import 'package:intl/intl.dart';

// DateTime tryParseDate(String dateStr) {
//   try {
//     return DateTime.parse(dateStr);
//   } catch (_) {
//     try {
//       return DateFormat("yyyy-MM-dd hh:mm a").parse(dateStr);
//     } catch (_) {
//       return DateTime(1970);
//     }
//   }
// }

// class RatingModel {
//   final int id;
//   final String name;
//   final String email;
//   final String rateText;
//   final int rate;
//   final String type;
//   final int parentId;
//   final DateTime createdAt;
//   final DateTime updatedAt;
//   final int? userId;

//   RatingModel({
//     required this.id,
//     required this.name,
//     required this.email,
//     required this.rateText,
//     required this.rate,
//     required this.type,
//     required this.parentId,
//     required this.createdAt,
//     required this.updatedAt,
//     this.userId,
//   });

//   factory RatingModel.fromJson(Map<String, dynamic> json) {
//     return RatingModel(
//       id: json['id'],
//       name: json['name'],
//       email: json['email'],
//       rateText: json['rateText'],
//       rate: int.parse(json['rate'].toString()),
//       type: json['type'],
//       parentId: json['parent_id'],
//       createdAt: tryParseDate(json['created_at']),
//       updatedAt: tryParseDate(json['updated_at']),
//       userId: json['user_id'],
//     );
//   }
// }
