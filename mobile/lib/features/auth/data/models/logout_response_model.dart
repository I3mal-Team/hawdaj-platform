class LogoutResponseModel {
  final int code;
  final String message;

  LogoutResponseModel({required this.code, required this.message});

  factory LogoutResponseModel.fromJson(Map<String, dynamic> json) {
    return LogoutResponseModel(code: json['code'], message: json['message']);
  }

  Map<String, dynamic> toJson() {
    return {'code': code, 'message': message};
  }
}
