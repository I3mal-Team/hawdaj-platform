// top_events_response.dart
import 'package:hawdaj/features/home/data/model/top_events_model/event_model.dart';

class TopEventsResponse {
  final int currentPage;
  final List<EventModel> items;
  final int total;

  TopEventsResponse({
    required this.currentPage,
    required this.items,
    required this.total,
  });

  factory TopEventsResponse.fromJson(Map<String, dynamic> json) {
    return TopEventsResponse(
      currentPage: json['current_page'],
      items: (json['items'] as List)
          .map((e) => EventModel.fromJson(e))
          .toList(),
      total: json['total'],
    );
  }
}
