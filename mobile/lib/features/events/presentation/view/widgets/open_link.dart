import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';

Future<void> openLink(String url) async {
  if (url.trim().isEmpty) return;

  String normalizedUrl = url.trim();
  if (!normalizedUrl.startsWith('http://') &&
      !normalizedUrl.startsWith('https://') &&
      !normalizedUrl.startsWith('tel:') &&
      !normalizedUrl.startsWith('mailto:') &&
      !normalizedUrl.startsWith('sms:') &&
      !normalizedUrl.startsWith('whatsapp:')) {
    normalizedUrl = 'https://$normalizedUrl';
  }

  final Uri uri = Uri.parse(normalizedUrl);

  try {
    // We try to launch directly or check canLaunchUrl
    // In release mode, canLaunchUrl tends to return false if manifest is not perfectly configured
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    } else {
      // Fallback attempt
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    }
  } catch (e) {
    debugPrint('Could not launch $url: $e');
  }
}
