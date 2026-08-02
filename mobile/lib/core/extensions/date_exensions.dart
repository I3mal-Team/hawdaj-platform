import 'package:intl/intl.dart';

extension DateUtils on DateTime {
  bool isSame(DateTime? date) {
    if (date == null) return false;
    return year == date.year && month == date.month && day == date.day;
  }

  String get dateVersion {
    return "$year-$month-$day";
  }

  String get formattedVersion {
    final formatter = DateFormat('yyy-MM-dd');
    var res = formatter.format(this);
    return res;
  }
}
