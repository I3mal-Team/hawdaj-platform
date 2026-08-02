import 'user_model.dart';

class AuthResponseModel {
  final int code;
  final String message;
  final AuthDataModel? data;

  AuthResponseModel({required this.code, required this.message, this.data});

  factory AuthResponseModel.fromJson(Map<String, dynamic> json) {
    return AuthResponseModel(
      code: json['code'],
      message: json['message'],
      data: json['data'] != null ? AuthDataModel.fromJson(json['data']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {'code': code, 'message': message, 'data': data?.toJson()};
  }
}

class AuthDataModel {
  final UserModel user;
  final String token;

  AuthDataModel({required this.user, required this.token});

  factory AuthDataModel.fromJson(Map<String, dynamic> json) {
    return AuthDataModel(
      user: UserModel.fromJson(json['user']),
      token: json['token'],
    );
  }

  Map<String, dynamic> toJson() {
    return {'user': user.toJson(), 'token': token};
  }
}
