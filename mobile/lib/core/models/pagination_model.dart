class PaginationModel<T> {
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

  PaginationModel({
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

  factory PaginationModel.fromJson(
    Map<String, dynamic> json,
    T Function(Map<String, dynamic>) fromJsonT,
  ) {
    return PaginationModel<T>(
      currentPage: json['current_page'],
      items: (json['items'] as List)
          .map((item) => fromJsonT(item as Map<String, dynamic>))
          .toList(),
      firstPageUrl: json['first_page_url'],
      from: json['from'],
      lastPage: json['last_page'],
      lastPageUrl: json['last_page_url'],
      nextPageUrl: json['next_page_url'],
      path: json['path'],
      perPage: json['per_page'],
      prevPageUrl: json['prev_page_url'],
      to: json['to'],
      total: json['total'],
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

class ApiResponse<T> {
  final int code;
  final String message;
  final T data;

  ApiResponse({required this.code, required this.message, required this.data});

  factory ApiResponse.fromJson(
    Map<String, dynamic> json,
    T Function(Map<String, dynamic>) fromJsonT,
  ) {
    return ApiResponse<T>(
      code: json['code'],
      message: json['message'],
      data: fromJsonT(json['data']),
    );
  }

  Map<String, dynamic> toJson(Map<String, dynamic> Function(T) toJsonT) {
    return {'code': code, 'message': message, 'data': toJsonT(data)};
  }
}
