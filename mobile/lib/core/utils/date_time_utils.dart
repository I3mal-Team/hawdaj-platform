import 'package:intl/intl.dart';

class DateTimeUtils {
  static String dateToArabic(DateTime? date) {
    if (date == null) return '';
    // Parse the input date string

    // Define month names in Arabic
    Map<int, String> arabicMonths = {
      1: "يناير",
      2: "فبراير",
      3: "مارس",
      4: "ابريل",
      5: "مايو",
      6: "يونيو",
      7: "يوليو",
      8: "اغسطس",
      9: "سبتمبر",
      10: "اكتوبر",
      11: "نوفمبر",
      12: "ديسمبر",
    };

    // Format the date
    String formattedDate =
        '${date.day} ${arabicMonths[date.month]}, ${date.year}';

    return formattedDate;
  }

  static String convertDateToArabicFormat(String dateString) {
    // Parse the input date string
    DateTime date = DateTime.parse(dateString);

    // Define month names in Arabic
    Map<int, String> arabicMonths = {
      1: "يناير",
      2: "فبراير",
      3: "مارس",
      4: "ابريل",
      5: "مايو",
      6: "يونيو",
      7: "يوليو",
      8: "اغسطس",
      9: "سبتمبر",
      10: "اكتوبر",
      11: "نوفمبر",
      12: "ديسمبر",
    };

    // Format the date
    String formattedDate =
        '${date.day} ${arabicMonths[date.month]}, ${date.year}';

    return formattedDate;
  }

  static String formattedStartTime(time) {
    return DateFormat('d MMMM, y  - hh:mm a').format(time);
  }
}
