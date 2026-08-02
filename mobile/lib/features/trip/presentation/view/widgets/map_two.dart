import 'dart:ui' as ui;
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:xml/xml.dart';
import 'package:svg_path_parser/svg_path_parser.dart';

class Country {
  final String id;
  final String path;
  final String color;
  final String name;
  final String lat;
  final String lng;
  final Offset centroid;
  final double fontSize;

  Country({
    required this.id,
    required this.path,
    required this.color,
    required this.name,
    required this.lat,
    required this.lng,
    required this.centroid,
    required this.fontSize,
  });
}

class MapScreen extends StatefulWidget {
  const MapScreen({
    super.key,
    required this.onPick,
    this.initialCountryId,
    this.closeOnPick = true,
  });

  final ValueChanged<Country> onPick;
  final String? initialCountryId;
  final bool closeOnPick;

  @override
  _MapScreenState createState() => _MapScreenState();
}

class _MapScreenState extends State<MapScreen> {
  List<Country> countries = [];
  Country? currentCountry;

  @override
  void initState() {
    super.initState();
    loadCountries();
  }

  Future<void> loadCountries() async {
    countries = await loadSvgImage(svgImage: "assets/images/map.svg");

    // ✅ لو فيه initialCountryId ظلّلها
    if (widget.initialCountryId != null) {
      final found = countries.firstWhere(
        (c) => c.id == widget.initialCountryId,
        orElse: () => countries.isNotEmpty ? countries.first : null as Country,
      );
      if (mounted && found != null) {
        setState(() => currentCountry = found);
      }
    }
    if (mounted) setState(() {});
  }

  // Future<void> loadCountries() async {
  //   countries = await loadSvgImage(svgImage: "assets/images/map.svg");
  //   setState(() {});
  // }

  static Future<List<Country>> loadSvgImage({required String svgImage}) async {
    List<Country> maps = [];
    String generalString = await rootBundle.loadString(svgImage);

    try {
      XmlDocument document = XmlDocument.parse(generalString);
      final paths = document.findAllElements('path');
      final texts = document.findAllElements('text');

      for (var element in paths) {
        String partId = element.getAttribute('id').toString();
        String partPath = element.getAttribute('d').toString();
        String name = element.getAttribute('name').toString();
        String color = element.getAttribute('fill')?.toString() ?? 'D7D3D2';
        String lat = element.getAttribute('data-lat')?.toString() ?? "23.8859";
        String long = element.getAttribute('data-lng')?.toString() ?? "45.0792";

        XmlElement? textElement;
        try {
          textElement = texts.firstWhere(
            (text) => text.getAttribute('id') == partId,
          );
        } catch (e) {
          textElement = null;
        }

        double textX = 0.0;
        double textY = 0.0;
        double fontSize = 16.0;

        if (textElement != null) {
          textX = double.tryParse(textElement.getAttribute('x') ?? '0') ?? 0.0;
          textY = double.tryParse(textElement.getAttribute('y') ?? '0') ?? 0.0;
          fontSize =
              double.tryParse(textElement.getAttribute('font-size') ?? '16') ??
              16.0;
        }

        // Calculate the centroid of the SVG path
        final centroid = _calculateCentroid(partPath);

        maps.add(
          Country(
            id: partId,
            path: partPath,
            color: color,
            name: name,
            lat: lat,
            lng: long,
            centroid: centroid,
            fontSize: fontSize,
          ),
        );
      }
    } catch (e) {
      print('Error parsing SVG file: $e');
    }

    return maps;
  }

  // Function to calculate the centroid of a path
  static Offset _calculateCentroid(String path) {
    final ui.Path parsedPath = parseSvgPath(path);
    final Rect bounds = parsedPath.getBounds();
    return Offset(bounds.center.dx, bounds.center.dy);
  }

  Color _parseColor(String colorString) {
    try {
      final colorHex = colorString.replaceAll('#', '');
      if (colorHex.length == 6) {
        return Color(int.parse('FF$colorHex', radix: 16));
      } else {
        return Colors.grey; // Default color
      }
    } catch (e) {
      print('Error parsing color: $e');
      return Colors.grey; // Default color
    }
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text(
          '${currentCountry?.name ?? ''}'.toUpperCase(),
          style: TextStyle(
            fontSize: 20.0,
            fontWeight: FontWeight.bold,
            color: Colors.white,
          ),
        ),
        SizedBox(
          width: MediaQuery.of(context).size.width,
          height: MediaQuery.of(context).size.height * 0.4,
          child: InteractiveViewer(
            minScale: 0.1,
            maxScale: 3.0,
            child: Stack(
              alignment: Alignment.center,
              children: [
                for (var country in countries)
                  _getClippedImage(
                    clipper: Clipper(scaleFactor: 0.4, svgPath: country.path),
                    color:
                        _parseColor(
                          currentCountry?.id == country.id
                              ? '#b56131' // Selected color
                              : country.color,
                        ).withOpacity(
                          currentCountry == null
                              ? 1.0
                              : currentCountry?.id == country.id
                              ? 1.0
                              : 1.0,
                        ),
                    country: country,
                    onCountrySelected: onCountrySelected,
                  ),
                for (var country in countries)
                  Positioned(
                    left:
                        country.centroid.dx * 0.4 - 15, // ← يتحرك شمال 15 بكسل
                    top: country.centroid.dy * 0.4 - 10, // ↑ يتحرك فوق 10 بكسل
                    child: Text(
                      country.name,
                      style: TextStyle(
                        fontSize: country.fontSize * 0.4,
                        color: Colors.black,
                      ),
                    ),
                  ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _getClippedImage({
    required Clipper clipper,
    required Color color,
    required Country country,
    final Function(Country country)? onCountrySelected,
  }) {
    return ClipPath(
      clipper: clipper,
      child: GestureDetector(
        onTap: () => onCountrySelected?.call(country),
        child: Container(color: color),
      ),
    );
  }

  void onCountrySelected(Country country) {
    setState(() => currentCountry = country);

    // ✅ رجع الاختيار للوالد
    widget.onPick(country);

    // ✅ اقفل تلقائيًا لو مطلوبة
    if (widget.closeOnPick) {
      Navigator.of(context).maybePop();
    }
  }
}

class Clipper extends CustomClipper<Path> {
  Clipper({required this.svgPath, required this.scaleFactor});

  final String svgPath;
  final double scaleFactor;

  @override
  Path getClip(Size size) {
    var path = parseSvgPath(svgPath);

    final Matrix4 matrix4 = Matrix4.identity()..scale(scaleFactor, scaleFactor);

    path = path.transform(matrix4.storage).shift(Offset.zero);

    return path;
  }

  @override
  bool shouldReclip(CustomClipper oldClipper) {
    return false;
  }
}
