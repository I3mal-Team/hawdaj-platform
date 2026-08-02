import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:hawdaj/features/tour%D9%80guide/presentation/manager/fetch_tour_guide_by_top_rated_cubit/fetch_tour_guide_by_top_rated_cubit.dart';
import 'package:shimmer/shimmer.dart';

class TopRatedTourGuidesList extends StatelessWidget {
  const TopRatedTourGuidesList({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<
      FetchTourGuideByTopRatedCubit,
      FetchTourGuideByTopRatedState
    >(
      builder: (context, state) {
        if (state is FetchTourGuideByTopRatedLoading) {
          return const Center(child: CircularProgressIndicator());
        } else if (state is FetchTourGuideByTopRatedError) {
          return Center(child: Text(state.message));
        } else if (state is FetchTourGuideByTopRatedSuccess) {
          final guides = state.fetchTourGuideByTopRated;
          if (guides.isEmpty) {
            return const Center(child: Text('لا يوجد مرشدين متاحين'));
          }

          return SizedBox(
            height: 220, // ارتفاع الصف بالكامل
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: guides.length,
              itemBuilder: (context, index) {
                final guide = guides[index];
                return Container(
                  width: 160,
                  margin: const EdgeInsets.symmetric(horizontal: 8),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // صورة المرشد
                      ClipRRect(
                        borderRadius: BorderRadius.circular(12),
                        child: CachedNetworkImage(
                          imageUrl: guide.fullImageUrl,
                          height: 140,
                          width: 160,
                          fit: BoxFit.cover,
                          placeholder: (context, url) => Shimmer.fromColors(
                            baseColor: Colors.grey.shade300,
                            highlightColor: Colors.grey.shade100,
                            child: Container(
                              height: 140,
                              width: 160,
                              color: Colors.white,
                            ),
                          ),
                          errorWidget: (context, url, error) =>
                              const Icon(Icons.broken_image),
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        guide.nickName ?? "",
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        guide.locationText,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(
                          fontSize: 12,
                          color: Colors.grey,
                        ),
                      ),
                    ],
                  ),
                );
              },
            ),
          );
        }

        return const SizedBox();
      },
    );
  }
}
