import 'package:hawdaj/core/components/custom_failure_toast.dart';
import 'package:url_launcher/url_launcher.dart';

void openFileLink(String fileUrl) async {
  if (fileUrl.trim().isEmpty) return;

  String normalizedUrl = fileUrl.trim();
  if (!normalizedUrl.startsWith('http://') &&
      !normalizedUrl.startsWith('https://')) {
    normalizedUrl = 'https://$normalizedUrl';
  }

  final uri = Uri.parse(normalizedUrl);
  try {
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    } else {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    }
  } catch (e) {
    showCustomFailureToast('Failed to open file');
  }
}
