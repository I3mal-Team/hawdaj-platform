/// Utility class for formatting time in Arabic
class ArabicTimeFormatter {
  /// Converts milliseconds to Arabic time format
  /// Example: "متبقي على الجلسة يومين , 11 ساعة , 4 دقائق , 22 ثانية"
  static String formatTimeInArabic(double milliseconds) {
    if (milliseconds <= 0) {
      return 'انتهت فترة الانتظار';
    }

    // Convert milliseconds to total seconds
    int totalSeconds = (milliseconds / 1000).floor();

    // Calculate time units
    int days = totalSeconds ~/ (24 * 60 * 60);
    int remainingAfterDays = totalSeconds % (24 * 60 * 60);

    int hours = remainingAfterDays ~/ (60 * 60);
    int remainingAfterHours = remainingAfterDays % (60 * 60);
    int minutes = remainingAfterHours ~/ 60;
    int seconds = remainingAfterHours % 60;

    List<String> timeParts = [];

    if (days > 0) {
      timeParts.add(_formatArabicTimeUnit(days, _getArabicDayUnit(days)));
    }

    if (hours > 0) {
      timeParts.add(_formatArabicTimeUnit(hours, _getArabicHourUnit(hours)));
    }

    if (minutes > 0) {
      timeParts
          .add(_formatArabicTimeUnit(minutes, _getArabicMinuteUnit(minutes)));
    }

    if (seconds > 0) {
      timeParts
          .add(_formatArabicTimeUnit(seconds, _getArabicSecondUnit(seconds)));
    }

    if (timeParts.isEmpty) {
      return 'أقل من ثانية واحدة';
    }

    String timeString = timeParts.join(' , ');
    return 'متبقي على الجلسة $timeString';
  }

  /// Get the appropriate Arabic unit for days based on the number
  static String _getArabicDayUnit(int days) {
    if (days == 1) {
      return 'يوم';
    } else if (days == 2) {
      return 'يومين';
    } else if (days >= 3 && days <= 10) {
      return 'أيام';
    } else {
      return 'يوماً';
    }
  }

  /// Get the appropriate Arabic unit for hours based on the number
  static String _getArabicHourUnit(int hours) {
    if (hours == 1) {
      return 'ساعة';
    } else if (hours == 2) {
      return 'ساعتين';
    } else if (hours >= 3 && hours <= 10) {
      return 'ساعات';
    } else {
      return 'ساعة';
    }
  }

  /// Get the appropriate Arabic unit for minutes based on the number
  static String _getArabicMinuteUnit(int minutes) {
    if (minutes == 1) {
      return 'دقيقة';
    } else if (minutes == 2) {
      return 'دقيقتين';
    } else if (minutes >= 3 && minutes <= 10) {
      return 'دقائق';
    } else {
      return 'دقيقة';
    }
  }

  /// Get the appropriate Arabic unit for seconds based on the number
  static String _getArabicSecondUnit(int seconds) {
    if (seconds == 1) {
      return 'ثانية';
    } else if (seconds == 2) {
      return 'ثانيتين';
    } else if (seconds >= 3 && seconds <= 10) {
      return 'ثوانٍ';
    } else {
      return 'ثانية';
    }
  }

  /// Format a time unit with proper Arabic grammar
  /// For dual forms (يومين، ساعتين، etc.), don't include the number
  /// For singular and plural forms, include the number
  static String _formatArabicTimeUnit(int number, String unit) {
    // For dual forms, don't include the number
    if ((number == 2) && (unit.endsWith('ين') || unit.endsWith('تين'))) {
      return unit;
    }
    // For all other cases, include the number
    return '$number $unit';
  }
}
