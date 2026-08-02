import 'data.dart';

class GlodalSearch {
  int? code;
  String? message;
  Data? data;

  GlodalSearch({this.code, this.message, this.data});

  factory GlodalSearch.fromJson(Map<String, dynamic> json) => GlodalSearch(
        code: json['code'] as int?,
        message: json['message'] as String?,
        data: json['data'] == null
            ? null
            : Data.fromJson(json['data'] as Map<String, dynamic>),
      );

  Map<String, dynamic> toJson() => {
        'code': code,
        'message': message,
        'data': data?.toJson(),
      };
}
