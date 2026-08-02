class RegisterRequestModel {
  final String email;
  final String firstName;
  final String lastName;
  final String password;

  RegisterRequestModel({
    required this.email,
    required this.firstName,
    required this.lastName,
    required this.password,
  });

  Map<String, dynamic> toJson() {
    return {
      'email': email,
      'first_name': firstName,
      'last_name': lastName,
      'password': password,
    };
  }
}
