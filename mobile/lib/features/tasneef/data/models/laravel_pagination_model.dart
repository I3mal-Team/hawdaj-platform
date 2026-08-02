class LaravelPaginationModel<T> {
  final int currentPage;
  final List<T> items;
  final String firstPageUrl;
  final int from;
  final int lastPage;
  final String lastPageUrl;
  final String? nextPageUrl;
  final String path;
  final dynamic perPage;
  final String? prevPageUrl;
  final int to;
  final int total;

  LaravelPaginationModel({
    required this.currentPage,
    required this.items,
    required this.firstPageUrl,
    required this.from,
    required this.lastPage,
    required this.lastPageUrl,
    this.nextPageUrl,
    required this.path,
    required this.perPage,
    this.prevPageUrl,
    required this.to,
    required this.total,
  });

  factory LaravelPaginationModel.fromJson(
    Map<String, dynamic> json,
    T Function(Map<String, dynamic>) fromJsonT,
  ) {
    return LaravelPaginationModel<T>(
      currentPage: json['current_page'] ?? 1,
      items: (json['items'] as List? ?? [])
          .map((item) => fromJsonT(item as Map<String, dynamic>))
          .toList(),
      firstPageUrl: json['first_page_url'] ?? '',
      from: json['from'] ?? 0,
      lastPage: json['last_page'] ?? 1,
      lastPageUrl: json['last_page_url'] ?? '',
      nextPageUrl: json['next_page_url'],
      path: json['path'] ?? '',
      perPage: json['per_page'] ?? 10,
      prevPageUrl: json['prev_page_url'],
      to: json['to'] ?? 0,
      total: json['total'] ?? 0,
    );
  }

  Map<String, dynamic> toJson(Map<String, dynamic> Function(T) toJsonT) {
    return {
      'current_page': currentPage,
      'items': items.map((item) => toJsonT(item)).toList(),
      'first_page_url': firstPageUrl,
      'from': from,
      'last_page': lastPage,
      'last_page_url': lastPageUrl,
      'next_page_url': nextPageUrl,
      'path': path,
      'per_page': perPage,
      'prev_page_url': prevPageUrl,
      'to': to,
      'total': total,
    };
  }
}
