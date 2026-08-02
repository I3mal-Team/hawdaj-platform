import 'package:hawdaj/features/exploration/data/model/glodal_search/item.dart';

class Data {
  int? currentPage;
  List<ItemGlodal>? items;
  String? firstPageUrl;
  int? from;
  int? lastPage;
  String? lastPageUrl;
  String? nextPageUrl;
  String? path;
  String? perPage;
  dynamic prevPageUrl;
  int? to;
  int? total;

  Data({
    this.currentPage,
    this.items,
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

  factory Data.fromJson(Map<String, dynamic> json) => Data(
    currentPage: json['current_page'] as int?,
    items: (json['items'] as List<dynamic>?)
        ?.map((e) => ItemGlodal.fromJson(e as Map<String, dynamic>))
        .toList(),
    firstPageUrl: json['first_page_url'] as String?,
    from: json['from'] as int?,
    lastPage: json['last_page'] as int?,
    lastPageUrl: json['last_page_url'] as String?,
    nextPageUrl: json['next_page_url'] as String?,
    path: json['path'] as String?,
    perPage: json['per_page'] as String?,
    prevPageUrl: json['prev_page_url'] as dynamic,
    to: json['to'] as int?,
    total: json['total'] as int?,
  );

  Map<String, dynamic> toJson() => {
    'current_page': currentPage,
    'items': items?.map((e) => e.toJson()).toList(),
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
