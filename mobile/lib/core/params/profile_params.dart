class ProfileParams {
  final String name;
  final String email;
  final String phone;
  final int idNumber;
  final String gender;
  ProfileParams({
    required this.name,
    required this.email,
    required this.phone,
    required this.idNumber,
    required this.gender,
  });
  Map<String, dynamic> toJson() => {
        'name': name,
        'email': email,
        'phone': phone,
        'idNumber': idNumber,
        'gender': gender
      };
}
