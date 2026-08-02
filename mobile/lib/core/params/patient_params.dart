class PatientParams {
  final String name;
  final String lastName;
  final String latestDiagnosis;
  final String gender;
  final String birthDate;
  final String? attachedFiles;
  PatientParams({
    required this.name,
    required this.lastName,
    required this.latestDiagnosis,
    required this.gender,
    required this.birthDate,
    this.attachedFiles,
  });

  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'lastName': lastName,
      'latestDiagnosis': latestDiagnosis,
      'gender': gender,
      'birthDate': birthDate,
      'attachedFiles': attachedFiles
    };
  }
}
