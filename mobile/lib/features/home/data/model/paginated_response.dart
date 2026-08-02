class PaginatedResponse<T> {
  final int? currentPage;
  final List<T>? data;
  final String? firstPageUrl;
  final dynamic from;
  final int? lastPage;
  final String? lastPageUrl;
  final dynamic nextPageUrl;
  final String? path;
  final int? perPage;
  final dynamic prevPageUrl;
  final dynamic to;
  final int? total;

  PaginatedResponse({
    this.currentPage,
    this.data,
    this.firstPageUrl,
    this.from,
    this.lastPage,
    this.lastPageUrl,
    this.nextPageUrl,
    this.path,
    this.perPage,
    this.prevPageUrl,
    this.to,
    this.total,
  });

  @override
  String toString() {
    return 'PaginatedResponse(currentPage: $currentPage, data: $data, firstPageUrl: $firstPageUrl, from: $from, lastPage: $lastPage, lastPageUrl: $lastPageUrl, nextPageUrl: $nextPageUrl, path: $path, perPage: $perPage, prevPageUrl: $prevPageUrl, to: $to, total: $total)';
  }

  factory PaginatedResponse.fromJson(
    Map<String, dynamic> json,
    T Function(Map<String, dynamic>) fromJsonT,
  ) {
    // انزل تدريجيًا إلى العقدة التي تحتوي عناصر الترقيم
    dynamic node = json;

    if (node is Map<String, dynamic> && node['data'] != null) {
      node = node['data'];
    }
    if (node is Map<String, dynamic> && node['trips'] != null) {
      node = node['trips'];
    }

    // الآن node إمّا:
    // - Map فيها مفاتيح الترقيم + items/results
    // - List من العناصر مباشرة
    // - null
    if (node == null) {
      return PaginatedResponse<T>(data: const []);
    }

    // الحالة: قائمة مباشرة
    if (node is List) {
      final parsedList = node
          .whereType<Map<String, dynamic>>()
          .map(fromJsonT)
          .toList();

      return PaginatedResponse<T>(data: parsedList);
    }

    // الحالة: Map فيها عناصر ومعلومات ترقيم
    if (node is Map<String, dynamic>) {
      final listDyn =
          (node['items'] ?? node['results'] ?? node['data'])
              as List<dynamic>? ??
          const [];
      final parsedList = listDyn
          .whereType<Map<String, dynamic>>()
          .map(fromJsonT)
          .toList();

      return PaginatedResponse<T>(
        currentPage: _toInt(node['current_page']),
        data: parsedList,
        firstPageUrl: node['first_page_url']?.toString(),
        from: node['from'],
        lastPage: _toInt(node['last_page']),
        lastPageUrl: node['last_page_url']?.toString(),
        nextPageUrl: node['next_page_url'],
        path: node['path']?.toString(),
        perPage: _toInt(node['per_page']),
        prevPageUrl: node['prev_page_url'],
        to: node['to'],
        total: _toInt(node['total']),
      );
    }

    // غير مدعوم
    throw Exception("Unsupported pagination format: ${node.runtimeType}");
  }

  Map<String, dynamic> toJson(Map<String, dynamic> Function(T) toJsonT) => {
    'current_page': currentPage,
    'data': data?.map((e) => toJsonT(e)).toList(),
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

  static int? _toInt(dynamic v) {
    if (v == null) return null;
    if (v is int) return v;
    return int.tryParse(v.toString());
  }
}
