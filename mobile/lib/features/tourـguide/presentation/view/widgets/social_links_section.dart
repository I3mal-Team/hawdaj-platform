import 'package:flutter/material.dart';
import 'package:hawdaj/features/home/data/model/guide_model/social_model.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/platform_color.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/view/widgets/platform_icon.dart';
import 'package:url_launcher/url_launcher.dart';

class SocialLinksSection extends StatelessWidget {
  const SocialLinksSection({super.key, required this.socialModel});
  final SocialModel socialModel;

  @override
  Widget build(BuildContext context) {
    // كوّن خريطة الروابط من الموديل
    final Map<String, String?> links = {
      'X': socialModel.twitter,
      'Instagram': socialModel.instagram,
      'LinkedIn': socialModel.linkedin,

      'YouTube': socialModel.youtube,
      'Personal Site': socialModel.personalAccount,
    };

    // فلترة أي null أو نص فاضي
    final entries = links.entries
        .where((e) => (e.value ?? '').trim().isNotEmpty)
        .toList();

    if (entries.isEmpty) return const SizedBox.shrink();

    String _norm(String s) =>
        (s.startsWith('http://') || s.startsWith('https://'))
        ? s
        : 'https://$s';

    Future<void> _open(String raw) async {
      final uri = Uri.tryParse(_norm(raw));
      if (uri == null) return;
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    }

    return Wrap(
      spacing: 8,
      runSpacing: 8,
      children: entries.map((e) {
        final name = e.key; // مثال: 'Facebook' أو 'Personal Site'
        final url = e.value!.trim(); // مضمون إنه مش فاضي بعد الفلترة

        return InkWell(
          onTap: () => _open(url),
          borderRadius: BorderRadius.circular(8),
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            decoration: BoxDecoration(
              color: platformColor(name),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(platformIcon(name), color: Colors.white, size: 18),
                const SizedBox(width: 6),
                Text(
                  name == 'Personal Site' ? 'الموقع الشخصي' : name,
                  style: const TextStyle(color: Colors.white),
                ),
              ],
            ),
          ),
        );
      }).toList(),
    );
  }
}
