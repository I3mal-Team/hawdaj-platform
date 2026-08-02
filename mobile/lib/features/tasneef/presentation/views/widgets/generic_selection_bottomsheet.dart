import 'package:flutter/material.dart';

class GenericSelectionBottomModal<T> extends StatelessWidget {
  final List<T> items;
  final String title;
  final String Function(T) displayText;
  final void Function(T) onItemSelected;
  final String? emptyMessage;
  final bool isLoading;
  final String? errorMessage;

  const GenericSelectionBottomModal({
    Key? key,
    required this.items,
    required this.title,
    required this.displayText,
    required this.onItemSelected,
    this.emptyMessage,
    this.isLoading = false,
    this.errorMessage,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(20),
          topRight: Radius.circular(20),
        ),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Handle bar
          Container(
            margin: const EdgeInsets.only(top: 8),
            width: 40,
            height: 4,
            decoration: BoxDecoration(
              color: Colors.grey[300],
              borderRadius: BorderRadius.circular(2),
            ),
          ),

          // Title
          Padding(
            padding: const EdgeInsets.all(16),
            child: Text(
              title,
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
          ),

          // Content
          Flexible(child: _buildContent()),
        ],
      ),
    );
  }

  Widget _buildContent() {
    if (isLoading) {
      return const Padding(
        padding: EdgeInsets.all(32),
        child: Center(child: CircularProgressIndicator()),
      );
    }

    if (errorMessage != null) {
      return Padding(
        padding: const EdgeInsets.all(16),
        child: Center(
          child: Text(
            errorMessage!,
            style: const TextStyle(color: Colors.red),
            textAlign: TextAlign.center,
          ),
        ),
      );
    }

    if (items.isEmpty) {
      return Padding(
        padding: const EdgeInsets.all(16),
        child: Center(
          child: Text(
            emptyMessage ?? 'لا توجد عناصر متاحة',
            style: const TextStyle(color: Colors.grey),
            textAlign: TextAlign.center,
          ),
        ),
      );
    }

    return ListView.builder(
      shrinkWrap: true,
      itemCount: items.length,
      itemBuilder: (context, index) {
        final item = items[index];
        return ListTile(
          title: Text(displayText(item)),
          onTap: () {
            onItemSelected(item);
            Navigator.of(context).pop();
          },
        );
      },
    );
  }

  static Future<T?> show<T>({
    required BuildContext context,
    required List<T> items,
    required String title,
    required String Function(T) displayText,
    String? emptyMessage,
    bool isLoading = false,
    String? errorMessage,
  }) {
    return showModalBottomSheet<T>(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => GenericSelectionBottomModal<T>(
        items: items,
        title: title,
        displayText: displayText,
        onItemSelected: (item) => Navigator.of(context).pop(item),
        emptyMessage: emptyMessage,
        isLoading: isLoading,
        errorMessage: errorMessage,
      ),
    );
  }

  // New method for inline selection that doesn't use modal
  static void showInlineSelection<T>({
    required BuildContext context,
    required List<T> items,
    required String title,
    required String Function(T) displayText,
    required void Function(T) onItemSelected,
    String? emptyMessage,
    bool isLoading = false,
    String? errorMessage,
  }) {
    showModalBottomSheet(
      context: context,
      builder: (context) => Container(
        constraints: const BoxConstraints(maxHeight: 400),
        child: GenericSelectionBottomModal<T>(
          items: items,
          title: title,
          displayText: displayText,
          onItemSelected: (item) {
            onItemSelected(item);
          },
          emptyMessage: emptyMessage,
          isLoading: isLoading,
          errorMessage: errorMessage,
        ),
      ),
    );
  }
}
