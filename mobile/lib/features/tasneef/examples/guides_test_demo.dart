import 'package:flutter/material.dart';
import '../data/models/unified_place_model.dart';
import '../presentation/views/widgets/tasneef_tour_guides_item_card.dart';

/// Demo data to test guide implementation
class GuidesTestDemo {
  /// Sample guide response data based on the API response
  static Map<String, dynamic> sampleGuideJson = {
    "id": 9,
    "type": "guide",
    "name": "محمد حسن",
    "nickName": "محمد",
    "description": "مرشد سياحي متخصص في الجولات التاريخية.",
    "image": "uploads/front_assets/imgs/zad1.jpg",
    "show_in_home": true,
    "experience": 8,
    "gender": "male",
    "regions": [
      {"id": 2, "name": "مكة المكرمة"},
    ],
    "languages": [
      {"id": 1, "name": "العربية"},
      {"id": 2, "name": "الانجليزية"},
    ],
    "social": {
      "facebook": "https://facebook.com/mohammed",
      "twitter": "https://twitter.com/mohammed",
      "instagram": "https://instagram.com/mohammed",
      "linkedin": "https://linkedin.com/in/mohammed",
      "personal_account": "https://mohammed.com",
    },
    "rate": 5,
    "ratings": [
      {
        "id": 282,
        "name": "zxczx zxczx",
        "email": "medorady192@gmail.com",
        "rateText": "جيد جدا ومبهر",
        "rate": "5",
        "type": "guide",
        "parent_id": 9,
        "created_at": "2025-02-28 03:08 PM",
        "updated_at": "2025-02-28T12:08:17.000000Z",
        "user_id": 118,
      },
    ],
    "galleries": [],
    "slug": "mohammed-hassan-guide",
    "categories": [],
    "address": "مكة المكرمة",
    "addressType": "guide",
    "active": 1,
    "viewsNum": 100,
    "featured": 1,
    "lat": 21.4225,
    "long": 39.8262,
    "visited": 0,
    "title": "محمد حسن",
    "review": 5,
    "is_favorite": false,
    "is_saved": false,
  };

  /// Test the guide model parsing and card display
  static void testGuideModelParsing() {
    try {
      final guide = UnifiedPlaceModel.fromJson(sampleGuideJson);

      // Test guide-specific properties
      assert(guide.isGuide, 'Should be identified as guide');
      assert(guide.nickName == 'محمد', 'Nickname should be parsed correctly');
      assert(guide.experience == 8, 'Experience should be parsed correctly');
      assert(guide.gender == 'male', 'Gender should be parsed correctly');
      assert(guide.regions.isNotEmpty, 'Regions should be parsed');
      assert(guide.languages.isNotEmpty, 'Languages should be parsed');
      assert(guide.social != null, 'Social links should be parsed');

      // Test helper methods
      assert(
        guide.languagesText.contains('العربية'),
        'Languages text should contain Arabic',
      );
      assert(
        guide.regionsText.contains('مكة المكرمة'),
        'Regions text should contain Mecca',
      );
      assert(
        guide.priceText == '8 سنة خبرة',
        'Price text should show experience',
      );
      assert(
        guide.locationText.contains('مكة المكرمة'),
        'Location text should show regions',
      );

      debugPrint('✅ Guide model parsing test passed!');
      debugPrint('Guide name: ${guide.title}');
      debugPrint('Nickname: ${guide.nickName}');
      debugPrint('Experience: ${guide.priceText}');
      debugPrint('Languages: ${guide.languagesText}');
      debugPrint('Regions: ${guide.regionsText}');
      debugPrint('Rating: ${guide.rate}');
      debugPrint('Ratings count: ${guide.ratings.length}');
      debugPrint('Image URL: ${guide.fullImageUrl}');
      debugPrint('Description: ${guide.description}');
      debugPrint('✅ All guide data fields are properly mapped!');
    } catch (e) {
      debugPrint('❌ Guide model parsing test failed: $e');
      rethrow;
    }
  }

  /// Sample widget to display guide information (Basic card for testing)
  static Widget buildGuideTestCard(UnifiedPlaceModel guide) {
    return Card(
      margin: const EdgeInsets.all(16),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              guide.title,
              style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            if (guide.nickName != null) ...[
              const SizedBox(height: 4),
              Text(
                'اللقب: ${guide.nickName}',
                style: const TextStyle(fontSize: 16, color: Colors.grey),
              ),
            ],
            const SizedBox(height: 8),
            Text(guide.description),
            const SizedBox(height: 8),
            Row(
              children: [
                Icon(Icons.star, color: Colors.amber, size: 16),
                const SizedBox(width: 4),
                Text('${guide.rate}/5'),
                const SizedBox(width: 16),
                Text(guide.priceText),
              ],
            ),
            const SizedBox(height: 8),
            if (guide.languagesText.isNotEmpty) ...[
              Row(
                children: [
                  const Icon(Icons.language, size: 16),
                  const SizedBox(width: 4),
                  Expanded(child: Text('اللغات: ${guide.languagesText}')),
                ],
              ),
              const SizedBox(height: 4),
            ],
            if (guide.regionsText.isNotEmpty) ...[
              Row(
                children: [
                  const Icon(Icons.location_on, size: 16),
                  const SizedBox(width: 4),
                  Expanded(child: Text('المناطق: ${guide.regionsText}')),
                ],
              ),
            ],
          ],
        ),
      ),
    );
  }

  /// Test widget that demonstrates the actual TasneefTourGuidesItemCard
  static Widget buildActualGuideCard(UnifiedPlaceModel guide) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Actual TasneefTourGuidesItemCard Test:',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          TasneefTourGuidesItemCard(guide: guide),
          const SizedBox(height: 20),
          const Text(
            'Data Mapping Summary:',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          _buildDataRow('Name', guide.title),
          _buildDataRow('Nickname', guide.nickName ?? 'N/A'),
          _buildDataRow('Experience', '${guide.experience} years'),
          _buildDataRow('Languages', guide.languagesText),
          _buildDataRow('Regions', guide.regionsText),
          _buildDataRow(
            'Rating',
            '${guide.rate}/5 (${guide.ratings.length} reviews)',
          ),
          _buildDataRow('Image URL', guide.fullImageUrl),
        ],
      ),
    );
  }

  static Widget _buildDataRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 2),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 100,
            child: Text(
              '$label:',
              style: const TextStyle(fontWeight: FontWeight.w600),
            ),
          ),
          Expanded(
            child: Text(value, style: const TextStyle(color: Colors.grey)),
          ),
        ],
      ),
    );
  }
}

/// Demo page to test guides implementation
class GuidesTestPage extends StatelessWidget {
  const GuidesTestPage({super.key});

  @override
  Widget build(BuildContext context) {
    // Run the test on build
    GuidesTestDemo.testGuideModelParsing();

    final guide = UnifiedPlaceModel.fromJson(GuidesTestDemo.sampleGuideJson);

    return Scaffold(
      appBar: AppBar(title: const Text('Guides Implementation Test')),
      body: SingleChildScrollView(
        child: Column(
          children: [
            const Padding(
              padding: EdgeInsets.all(16),
              child: Text(
                'Testing Guides Implementation',
                style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
              ),
            ),

            // Show both cards for comparison
            const Padding(
              padding: EdgeInsets.symmetric(horizontal: 16),
              child: Text(
                'Basic Test Card:',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.w600),
              ),
            ),
            GuidesTestDemo.buildGuideTestCard(guide),

            const Padding(
              padding: EdgeInsets.symmetric(horizontal: 16),
              child: Text(
                'Actual TasneefTourGuidesItemCard:',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.w600),
              ),
            ),
            GuidesTestDemo.buildActualGuideCard(guide),

            const Padding(
              padding: EdgeInsets.all(16),
              child: Text(
                '✅ Guide model parsing and display working correctly!\n✅ API data properly mapped to card components!',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 16,
                  color: Colors.green,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
