import 'package:cached_network_image/cached_network_image.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:flutter_avif/flutter_avif.dart';
import 'package:hawdaj/core/databases/api/end_points.dart';
import 'package:shimmer/shimmer.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';

class GalleryImageCarousel extends StatefulWidget {
  final List<String> images;
  final double height;
  final double borderRadius;

  const GalleryImageCarousel({
    super.key,
    required this.images,
    this.height = double.infinity,
    this.borderRadius = 0,
  });

  @override
  State<GalleryImageCarousel> createState() => _GalleryImageCarouselState();
}

class _GalleryImageCarouselState extends State<GalleryImageCarousel> {
  int activeIndex = 0;
  final CarouselSliderController _controller = CarouselSliderController();

  @override
  Widget build(BuildContext context) {
    bool _isAvifUrl(String url) {
      // تحقق من امتداد .avif (قد تحتاج إلى التعامل مع استعلامات أو باراميترات)
      return url.toLowerCase().endsWith('.avif');
    }

    return Stack(
      alignment: Alignment.bottomCenter,
      children: [
        ClipRRect(
          borderRadius: BorderRadius.circular(widget.borderRadius),
          child: CarouselSlider.builder(
            carouselController: _controller,
            itemCount: widget.images.length,

            itemBuilder: (context, index, realIndex) {
              final imagePath = widget.images[index];
              final fullUrl = imagePath;
              if (_isAvifUrl(fullUrl)) {
                // استخدم Avif / CachedNetworkAvifImage
                return CachedNetworkAvifImage(
                  fullUrl,
                  height: widget.height,
                  fit: BoxFit.cover,
                );
              }

              return CachedNetworkImage(
                imageUrl: widget.images[index],
                fit: BoxFit.cover,
                width: double.infinity,
                placeholder: (context, url) => Shimmer.fromColors(
                  baseColor: Colors.grey.shade300,
                  highlightColor: Colors.grey.shade100,
                  child: Container(
                    width: double.infinity,
                    height: widget.height,
                    color: Colors.white,
                  ),
                ),

                errorWidget: (context, url, error) =>
                    const Center(child: Icon(Icons.broken_image)),
              );
            },
            options: CarouselOptions(
              height: widget.height,
              viewportFraction: 1.0,
              autoPlay: true,
              onPageChanged: (index, reason) =>
                  setState(() => activeIndex = index),
            ),
          ),
        ),

        Positioned(
          bottom: 16, // تحكم في ارتفاع الدوتس
          child: AnimatedSmoothIndicator(
            activeIndex: activeIndex,
            count: widget.images.length,
            effect: ExpandingDotsEffect(
              activeDotColor: Colors.white,
              dotColor: Colors.white.withOpacity(0.4),
              dotHeight: 8,
              dotWidth: 8,
              expansionFactor: 2.5, // يجعل النقطة الفعالة بيضاوية الشكل
              spacing: 6,
              radius: 20,
            ),
            onDotClicked: (index) => _controller.animateToPage(index),
          ),
        ),
      ],
    );
  }
}
