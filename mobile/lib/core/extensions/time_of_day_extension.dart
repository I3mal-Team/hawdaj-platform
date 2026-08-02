import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

extension TimeOfDayExtension on TimeOfDay {
// is same
  bool isSame(TimeOfDay? time) {
    if (time == null) return false;
    return hour == time.hour && minute == time.minute;
  }

  String get stringVersion {
    final now = DateTime.now();
    final dateTime = DateTime(
      now.year,
      now.month,
      now.day,
      hour,
      minute,
    );

    final formatter = DateFormat('hh:mm a');
    return formatter.format(dateTime);
  }
}
