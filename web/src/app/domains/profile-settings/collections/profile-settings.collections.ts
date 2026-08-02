export class ProfileSettingsManagementCollections {
  static ModuleName: string = 'api';

  static Home: string = `${ProfileSettingsManagementCollections.ModuleName}/podcast`;

  // Property Options APIs (without ModuleName prefix since environment.apiUrl already includes /api)
  static Prices: string = 'prices';
  static Cities: string = 'cities';
  static Regions: string = 'regions';
  static Categories: string = 'categories';
  static FoodCategories: string = 'zads/food-categories';

  // Properties API
  static Properties: string = 'properties';
  static MyProperties: string = 'my-properties';
}
