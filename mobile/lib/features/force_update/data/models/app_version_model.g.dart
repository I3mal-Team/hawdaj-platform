// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'app_version_model.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

AppVersionModel _$AppVersionModelFromJson(Map<String, dynamic> json) =>
    AppVersionModel(
      version: json['version'] as String,
      isMandatory: json['isMandatory'] as bool,
    );

Map<String, dynamic> _$AppVersionModelToJson(AppVersionModel instance) =>
    <String, dynamic>{
      'version': instance.version,
      'isMandatory': instance.isMandatory,
    };
