import 'package:flutter/material.dart';
import 'package:hexcolor/hexcolor.dart';

abstract class AppColors {
  //# widgets
  static const Color successGreen = Color(0xFF00ADA8);
  static const Color errorRed = Color(0xffCA4146);
  static Color bg = HexColor('#081B2B');
  static Color primary = HexColor('#6A4690');
  static Color primaryLight = HexColor('#6A4690').withValues(alpha: 0.5);
  //#F2EBF6
  static Color primaryLight2 = HexColor('#F2EBF6');
  static Color inactive = HexColor('#E7EBEE');
  static Color inactive2 = HexColor('#F1F1F1');
  static Color inactive3 = HexColor('#F8F8F8');
  static Color inactive4 = HexColor('#DDDDDD');
  static Color inactive5 = HexColor('#EEEEEE');
  static Color uiBlack = HexColor('#090B0E');
  static Color dark = HexColor('#0D2C46');
  static Color dark2 = HexColor('#546881');
  static Color dark3 = HexColor('#0E324F');
  static Color dark4 = HexColor('#0B263D');
  static Color dark5 = HexColor('#3D4C5E');
  static Color blueBorder = HexColor('#1F80AA');
  static Color success = HexColor('#00AF6C');
  static Color lightSuccess = HexColor('#E5FAF2');
  static Color danger = HexColor('#F55157');
  static Color dangerLight1 = HexColor('#F9E7E8');
  static const Color yellowColor = Color(0xFFF79009);
  static Color danger2 = HexColor('#CA4146');

  //# text
  static Color inactiveText1 = HexColor('#A3ADBB');
  static Color inactiveText2 = HexColor('#DBE1E6');
  static Color inactiveText3 = HexColor('#B5C1CB');
  static Color inactiveText4 = HexColor('#EAF7FC');
  static Color inactiveText5 = HexColor('#A5A5A5');
  //#EEF2F6
  static Color inactiveText6 = HexColor('#EEF2F6');
  static const Color mainBlue = Color(0xFF29ABE2);
  static const Color darkBlue = Color(0xFF0E324F);
  static const Color darkBlue2 = Color(0xFF0D2C46);

  static const Color lightBlue = Color(0xFF2189B5);
  static const Color mainGrey = Color(0xFF546881);
  static const Color obsidianBlack = Color(0xFF090B0E);
  static const Color grey = Color(0xFF3D4C5E);
  static const Color lightGrey = Color(0xFFA3ADBB);
  static const Color white = Colors.white;
  static Color darkWhite = HexColor('#F8F8F8');
  static const Color black = Colors.black;
  static const Color lightWhite2 = Color(0xffFBFBFB);
  static const Color lightWhite3 = Color(0xFFF8F8F8);
  static const Color colorcommingItems = Color(0xFFE7EBEE);
  static const Color redcolor = Color(0xFFF55157);
  //#FDC800
  static const Color yellow = Color(0xFFFDC800);
  //0xFFF5F7F8
  static const Color dark50 = Color(0xFFF5F7F8);
  //0xFF4B5565
  static const Color dark60 = Color(0xFF4B5565);
  static const Color lightRed = Color(0xFFF9E7E8);
  //0xFFFCFCFD
  static const Color lightRed2 = Color(0xFFF8F8F8);
  //0xFF6A4690
  static const Color lightRed3 = Color(0xFF6A4690);
  //0xFF697586
  static const Color lightRed4 = Color(0xFF697586);
  //#EEF2F6
}
