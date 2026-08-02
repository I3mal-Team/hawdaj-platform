import 'package:flutter/material.dart';
import '../presentation/views/tasneef_places_list_view.dart';

/// Example showing how to use TasneefPlacesListView
class TasneefUsageDemo extends StatelessWidget {
  const TasneefUsageDemo({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Tasneef Demo',
      home: Scaffold(
        appBar: AppBar(title: const Text('Tasneef Demo')),
        body: Column(
          children: [
            // Example 1: All places
            ListTile(
              title: const Text('عرض جميع الأماكن'),
              subtitle: const Text('يعرض جميع الأماكن مع التصفح'),
              trailing: const Icon(Icons.arrow_forward_ios),
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => const TasneefPlacesListView(),
                ),
              ),
            ),
            const Divider(),
            // Example 2: Featured places only
            ListTile(
              title: const Text('الأماكن المميزة فقط'),
              subtitle: const Text('يعرض الأماكن المميزة فقط'),
              trailing: const Icon(Icons.arrow_forward_ios),
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) =>
                      const TasneefPlacesListView(topFeatured: true),
                ),
              ),
            ),
            const Divider(),
            // Example 3: Specific category
            ListTile(
              title: const Text('أماكن فئة المنتزهات'),
              subtitle: const Text('يعرض أماكن فئة محددة (المنتزهات)'),
              trailing: const Icon(Icons.arrow_forward_ios),
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => const TasneefPlacesListView(
                    categoryId: 18, // Parks category
                  ),
                ),
              ),
            ),
            const Divider(),
            // Example 4: Featured places in specific category
            ListTile(
              title: const Text('الأماكن المميزة في فئة محددة'),
              subtitle: const Text('مزج بين الفئة والتميز'),
              trailing: const Icon(Icons.arrow_forward_ios),
              onTap: () => Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => const TasneefPlacesListView(
                    categoryId: 19, // Historical sites
                    topFeatured: true,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
