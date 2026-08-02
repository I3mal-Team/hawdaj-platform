import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:url_launcher/url_launcher.dart';

class MapSection extends StatelessWidget {
  final double lat;
  final double long;

  const MapSection({super.key, required this.lat, required this.long});

  @override
  Widget build(BuildContext context) {
    return SliverToBoxAdapter(
      child: Container(
        width: double.infinity,
        height: 193.h,
        clipBehavior: Clip.antiAlias,
        decoration: ShapeDecoration(
          shape: RoundedRectangleBorder(
            side: const BorderSide(width: 1, color: Color(0xFFEEF2F6)),
            borderRadius: BorderRadius.circular(8),
          ),
        ),
        child: Stack(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(8),
              child: GoogleMap(
                initialCameraPosition: CameraPosition(
                  target: LatLng(lat, long),
                  zoom: 18.0,
                ),
                markers: {
                  Marker(
                    markerId: const MarkerId('place_location'),
                    position: LatLng(lat, long),
                  ),
                },
                zoomControlsEnabled: false,
                myLocationButtonEnabled: false,
              ),
            ),
            Positioned(
              bottom: 12,
              right: 12,
              child: ElevatedButton.icon(
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.white,
                  foregroundColor: Colors.blueAccent,
                  elevation: 2,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(6),
                  ),
                ),
                onPressed: () async {
                  final googleMapsUrl = Uri.parse(
                    'https://www.google.com/maps/dir/?api=1&destination=$lat,$long',
                  );

                  if (await canLaunchUrl(googleMapsUrl)) {
                    await launchUrl(
                      googleMapsUrl,
                      mode: LaunchMode.externalApplication,
                    );
                  } else {
                    // يمكنك عرض رسالة خطأ هنا
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(content: Text('لا يمكن فتح تطبيق الخرائط')),
                    );
                  }
                },

                icon: const Icon(Icons.directions),
                label: Text('directions'.tr()),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
