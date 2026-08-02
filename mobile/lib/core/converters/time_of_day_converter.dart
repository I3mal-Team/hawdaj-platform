// ignore_for_file: public_member_api_docs, sort_constructors_first

import 'package:hawdaj/core/extensions/time_of_day_extension.dart';
import 'package:flutter/material.dart';
import 'package:json_annotation/json_annotation.dart';

class TimeOfDayConverter implements JsonConverter<TimeOfDay, String> {
  const TimeOfDayConverter();

  @override
  TimeOfDay fromJson(String json) {
    var parts = json.split(' ');
    var timeParts = parts[0].split(':');
    int hour = int.parse(timeParts[0]);
    int minute = int.parse(timeParts[1]);

    // Convert 12-hour format to 24-hour format
    if (parts[1] == 'PM' && hour != 12) {
      hour += 12;
    } else if (parts[1] == 'AM' && hour == 12) {
      hour = 0;
    }

    var res = TimeOfDay(hour: hour, minute: minute);
    return res;
  }

  @override
  String toJson(TimeOfDay object) {
    return object.stringVersion;
  }
}
