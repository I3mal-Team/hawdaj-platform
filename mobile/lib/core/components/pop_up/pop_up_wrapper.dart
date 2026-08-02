import 'package:flutter/material.dart';
import 'package:hawdaj/core/components/pop_up/pop_up_item.dart';

class PopUpWrapper<T extends RawPopUpData> extends StatelessWidget {
  final List<T> items;
  final Widget? child;
  final Color? backgroundColor;
  final int? selectedId;
  final bool autoClose;

  final ValueChanged<T>? onChanged;

  const PopUpWrapper({
    super.key,
    required this.items,
    this.child,
    this.autoClose = false,
    this.backgroundColor,
    this.selectedId,
    this.onChanged,
  });

  bool last(T i) => items.last.id == i.id;

  @override
  Widget build(BuildContext context) {
    T? selectedItem = selectedId == null || items.isEmpty
        ? null
        : items.firstWhere(
            (i) => i.id == selectedId,
            orElse: () => items.first,
          );

    return PopupMenuButton<T>(
      color: backgroundColor ?? Colors.white,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
      padding: EdgeInsets.zero,
      onSelected: (selected) {
        onChanged?.call(selected);
      },
      itemBuilder: (BuildContext context) {
        return items.map((T item) {
          return PopupMenuItem<T>(
            value: item,
            padding: EdgeInsets.zero,
            child: GestureDetector(
              onTap: item.onTap == null
                  ? null
                  : () async {
                      await item.onTap!();
                      if (!context.mounted) return;
                      if (autoClose) Navigator.of(context).pop();
                    },
              child: Container(
                padding: const EdgeInsets.all(12),
                color: Colors.transparent,
                child: Row(
                  children: [
                    if (item.icon != null) ...[
                      item.icon!,
                      const SizedBox(width: 8),
                    ],
                    Text(item.title),
                  ],
                ),
              ),
            ),
          );
        }).toList();
      },
      child: child ?? const Icon(Icons.more_vert),
    );
  }
}
