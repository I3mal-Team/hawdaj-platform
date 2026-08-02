import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

IconData platformIcon(String p) {
  switch (p) {
    case 'LinkedIn':
      return FontAwesomeIcons.linkedin;
    case 'YouTube':
      return FontAwesomeIcons.youtube;
    case 'X':
      return FontAwesomeIcons.xTwitter;
    case 'Instagram':
      return FontAwesomeIcons.instagram;
    case 'TikTok':
      return FontAwesomeIcons.tiktok;
    case 'Personal Site':
      return FontAwesomeIcons.user;
    default:
      return Icons.link;
  }
}
