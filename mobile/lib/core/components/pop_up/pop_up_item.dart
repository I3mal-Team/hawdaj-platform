// ignore_for_file: public_member_api_docs, sort_constructors_first
import 'dart:async';
import 'dart:math';

import 'package:flutter/material.dart';

class RawPopUpData {
  final Widget? icon;
  final String title;
  final FutureOr<void> Function()? onTap;
  late int _id;

  RawPopUpData({
    this.icon,
    required this.title,
    this.onTap,
    int? id,
  }) {
    _id = id ?? Random.secure().nextInt(1000000);
  }
  int get id => _id;
  @override
  bool operator ==(Object other) {
    if (identical(this, other)) return true;

    return other is RawPopUpData && other.id == id;
  }

  @override
  int get hashCode => id.hashCode;

  @override
  String toString() {
    return 'RawPopUpData(icon: $icon, title: $title, onTap: $onTap, _id: $_id)';
  }
}

class GlobalPopUpData extends RawPopUpData {
  /// this should be the icon name from the assets/icons folder <br>
  /// assets/icons/$iconName.png
  final String? iconName;
  final Color? iconColor;
  GlobalPopUpData({
    required super.title,
    super.onTap,
    this.iconName,
    this.iconColor,
    super.id,
  }) : super(
          icon: iconWidget(iconName, iconColor),
        );

  static Widget? iconWidget(String? name, Color? color) {
    if (name == null) return null;
    return Image.asset(
      'assets/icons/$name.png',
      width: 20,
      height: 20,
      color: color,
    );
  }
}
