import 'package:cached_network_image/cached_network_image.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:hawdaj/features/home/presentation/manager/sliders_cubit/sliders_cubit.dart';
import 'package:hawdaj/features/home/presentation/manager/sliders_cubit/sliders_state.dart';
import 'package:shimmer/shimmer.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';

class HomeSlidersSection extends StatefulWidget {
  const HomeSlidersSection({super.key});

  @override
  State<HomeSlidersSection> createState() => _HomeSlidersSectionState();
}

class _HomeSlidersSectionState extends State<HomeSlidersSection> {
  int activeIndex = 0;

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<SlidersCubit, SlidersState>(
      builder: (context, state) {
        if (state is SlidersLoading) {
          return const SlidersShimmer();
        } else if (state is SlidersError) {
          return const SizedBox.shrink();
        } else if (state is SlidersLoaded) {
          if (state.sliders.isEmpty) return const SizedBox.shrink();
          return Column(
            children: [
              CarouselSlider.builder(
                itemCount: state.sliders.length,
                itemBuilder: (context, index, realIndex) {
                  final slider = state.sliders[index];
                  return GestureDetector(
                    onTap: () async {
                      if (slider.link != null && slider.link!.isNotEmpty) {
                        final Uri url = Uri.parse(slider.link!);
                        if (await canLaunchUrl(url)) {
                          await launchUrl(url);
                        }
                      }
                    },
                    child: Container(
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(16.r),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withOpacity(0.1),
                            blurRadius: 10,
                            offset: const Offset(0, 5),
                          ),
                        ],
                      ),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(16.r),
                        child: CachedNetworkImage(
                          imageUrl: slider.image,
                          fit: BoxFit.cover,
                          width: double.infinity,
                          placeholder: (context, url) => Shimmer.fromColors(
                            baseColor: Colors.grey[300]!,
                            highlightColor: Colors.grey[100]!,
                            child: Container(color: Colors.white),
                          ),
                          errorWidget: (context, url, error) => Container(
                            color: Colors.grey[200],
                            child: const Icon(Icons.error, color: Colors.grey),
                          ),
                        ),
                      ),
                    ),
                  );
                },
                options: CarouselOptions(
                  height: 180.h,
                  autoPlay: true,
                  autoPlayInterval: const Duration(seconds: 4),
                  enlargeCenterPage: true,
                  viewportFraction: 0.85,
                  onPageChanged: (index, reason) {
                    setState(() {
                      activeIndex = index;
                    });
                  },
                ),
              ),
              SizedBox(height: 12.h),
              AnimatedIndicator(
                activeIndex: activeIndex,
                count: state.sliders.length,
              ),
            ],
          );
        }
        return const SizedBox.shrink();
      },
    );
  }
}

class AnimatedIndicator extends StatelessWidget {
  final int activeIndex;
  final int count;

  const AnimatedIndicator({
    super.key,
    required this.activeIndex,
    required this.count,
  });

  @override
  Widget build(BuildContext context) {
    return AnimatedSmoothIndicator(
      activeIndex: activeIndex,
      count: count,
      effect: ExpandingDotsEffect(
        dotHeight: 8.h,
        dotWidth: 8.h,
        activeDotColor: Theme.of(context).primaryColor,
        dotColor: Colors.grey.withOpacity(0.3),
        spacing: 6.w,
      ),
    );
  }
}

class SlidersShimmer extends StatelessWidget {
  const SlidersShimmer({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 16.w),
      child: Shimmer.fromColors(
        baseColor: Colors.grey[300]!,
        highlightColor: Colors.grey[100]!,
        child: Container(
          height: 180.h,
          width: double.infinity,
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16.r),
          ),
        ),
      ),
    );
  }
}
