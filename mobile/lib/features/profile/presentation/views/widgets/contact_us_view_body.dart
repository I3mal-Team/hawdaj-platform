import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:hawdaj/core/components/primary_button.dart';
import 'package:hawdaj/core/utils/app_assets.dart';
import 'package:hawdaj/features/profile/presentation/views/widgets/contact_info_row.dart';
import 'package:hawdaj/features/trip/presentation/view/widgets/trip_start_app_bar.dart';
import 'package:url_launcher/url_launcher.dart';

class ContactUsViewBody extends StatelessWidget {
  const ContactUsViewBody({super.key});

  @override
  Widget build(BuildContext context) {
    return CustomScrollView(
      slivers: [
        SliverToBoxAdapter(
          child: TripStartAppBar(title: 'contact_us_title'.tr()),
        ),
        SliverFillRemaining(
          hasScrollBody: false,
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "contact_us_description".tr(),
                  style: TextStyle(
                    fontSize: 16,
                    height: 1.6,
                    color: Colors.black87,
                  ),
                  textAlign: TextAlign.start,
                ),
                const SizedBox(height: 24),
                ContactInfoRow(
                  iconPath: AppAssets.call,
                  text: '+966 50 870 7717',
                  onTap: () {
                    ContactActions.callPhone('+966508707717');
                  },
                ),
                Divider(color: Colors.grey.shade300),
                ContactInfoRow(
                  iconPath: AppAssets.sms,
                  text: 'hello@hawdaj.net',
                  onTap: () {
                    ContactActions.sendEmail(
                      'hello@hawdaj.net',
                      subject: 'استفسار من تطبيق Hawdaj',
                      body:
                          'مرحبًا فريق Hawdaj،\n\nأرغب في الاستفسار بخصوص ...',
                    );
                  },
                ),
                Divider(color: Colors.grey.shade300),
                ContactInfoRow(
                  iconPath: AppAssets.location,
                  text: "الرياض، المملكة العربية السعودية",
                  iconColor: Colors.grey,
                  onTap: () {
                    ContactActions.openMap(
                      address: 'الرياض، المملكة العربية السعودية',
                    );
                    // أو بالإحداثيات لو عندك:
                    // ContactActions.openMap(lat: 24.7136, lng: 46.6753);
                  },
                ),
                const SizedBox(height: 24),
                PrimaryButton(
                  title: 'contact_us_button'.tr(),
                  onTap: () {
                    ContactActions.openWhatsApp(
                      phone: '+966 50 870 7717',
                      message: 'مرحبًا، أود الاستفسار عن ...',
                    );
                  },
                  iconPath: AppAssets.whatsApp,
                  width: double.infinity,
                  height: 50.h,
                  textColor: Colors.white,
                  backgroundColor: Color(0xff60D669),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }
}

class ContactActions {
  /// اتصال هاتفي
  static Future<void> callPhone(String phone) async {
    final uri = Uri(scheme: 'tel', path: phone);
    if (!await launchUrl(uri, mode: LaunchMode.externalApplication)) {
      throw '${"cannot_call_phone".tr()} $phone';
    }
  }

  /// إرسال بريد إلكتروني
  static Future<void> sendEmail(
    String email, {
    String? subject,
    String? body,
  }) async {
    final uri = Uri(
      scheme: 'mailto',
      path: email,
      queryParameters: {
        if (subject != null && subject.isNotEmpty) 'subject': subject,
        if (body != null && body.isNotEmpty) 'body': body,
      },
    );
    if (!await launchUrl(uri, mode: LaunchMode.externalApplication)) {
      throw '${"cannot_open_mail".tr()} $email';
    }
  }

  // فتح محادثة واتساب
  static Future<void> openWhatsApp({
    required String phone,
    String? message,
  }) async {
    final encodedMessage = message != null
        ? Uri.encodeComponent(message)
        : null;
    final uri = Uri.parse(
      'https://wa.me/${phone.replaceAll('+', '')}'
      '${encodedMessage != null ? '?text=$encodedMessage' : ''}',
    );
    if (!await launchUrl(uri, mode: LaunchMode.externalApplication)) {
      throw '${"cannot_open_whatsapp".tr()} $phone';
    }
  }

  /// فتح الموقع على الخرائط
  /// يمكن استخدام عنوان نصّي أو إحداثيات (lat/lng)
  static Future<void> openMap({
    String? address,
    double? lat,
    double? lng,
  }) async {
    // نحاول geo: أولًا (أفضل للأندرويد)، ثم نعمل fallback إلى Google Maps URL
    Uri? geoUri;
    if (lat != null && lng != null) {
      geoUri = Uri.parse('geo:$lat,$lng?q=$lat,$lng');
    } else if (address != null && address.trim().isNotEmpty) {
      geoUri = Uri.parse('geo:0,0?q=${Uri.encodeComponent(address)}');
    }

    if (geoUri != null && await canLaunchUrl(geoUri)) {
      final ok = await launchUrl(geoUri, mode: LaunchMode.externalApplication);
      if (ok) return;
    }

    // Fallback: Google Maps URL (يعمل على iOS/Android/ويب)
    final query = lat != null && lng != null ? '$lat,$lng' : (address ?? '');
    final mapsWeb = Uri.parse(
      'https://www.google.com/maps/search/?api=1&query=${Uri.encodeComponent(query)}',
    );
    if (!await launchUrl(mapsWeb, mode: LaunchMode.externalApplication)) {
      throw 'cannot_open_maps'.tr();
    }
  }
}
