import 'package:json_annotation/json_annotation.dart';

part 'app_version_model.g.dart';

@JsonSerializable(explicitToJson: true)
class AppVersionModel {
  final String version;
  final bool isMandatory;
  AppVersionModel({required this.version, required this.isMandatory});

  factory AppVersionModel.fromJson(Map<String, dynamic> json) =>
      _$AppVersionModelFromJson(json);
  Map<String, dynamic> toJson() => _$AppVersionModelToJson(this);
}
