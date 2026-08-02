import 'package:flutter/material.dart';

class CustomHorizontalListView<T> extends StatelessWidget {
  final double spacing;
  final double? width;
  final List<T> models;
  final Widget Function(T model, int index) childBuilder;

  const CustomHorizontalListView({
    super.key,
    required this.spacing,
    this.width,
    required this.models,
    required this.childBuilder,
  });

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      scrollDirection: Axis.horizontal,
      child: IntrinsicHeight(
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: models.map((model) {
            bool isLast = models.last == model;
            int index = models.indexOf(model);
            return Container(
              margin: EdgeInsets.only(left: isLast ? 0 : spacing),
              width: width,
              child: childBuilder(model, index),
            );
          }).toList(),
        ),
      ),
    );
  }
}
