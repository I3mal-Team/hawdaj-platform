import 'package:hawdaj/core/utils/app_environment_manager.dart';

class EndPoints {
  //********  base url
  //static String baseUrl = 'https://dashboard.hawdaj.net/api/';
  //https://test.dashboard.hawdaj.net
  //baseUrl
  static String get baseUrl => AppEnvironmentManager().baseUrl;

  static String baseTestUrl = 'https://test.dashboard.hawdaj.net/api/';
  static String get imageUrl => AppEnvironmentManager().imageUrl;
  static String imageTestUrl = 'https://test.dashboard.hawdaj.net/';
  // static String get imageUploadUrl => AppEnvironmentManager().imageUploadUrl;
  static String imageTestUploadUrl =
      'https://test.dashboard.hawdaj.net/uploads/';

  //******* routes
  static const String helpEnums = '';
  static const String newHelpModels = '';
  static const String home = 'home';
  static const String sliders = 'sliders';
  //profile
  static const String profile = 'get-profile-page';
  //searchGlobal
  static String searchGlobal(int page) => 'global-search';
  //reprepareTrip
  static const String reprepareTrip = '/v2/trips/reprepare';
  static String newViewTrip(String token) => '/v2/trips/view/$token';

  //*********** rates
  static const String addRate = 'rates';
  //myProperties
  static const String myProperties = 'my-properties';
  //api/v2/trips/save
  static const String saveTripV2 = 'v2/trips/save';
  //*********** updatePassword
  static const String updatePassword = "update-password";
  // *********** landmark/list page
  static String landmarkList(int page) => 'landmark/list?page=$page';
  // *********** landmark/show/:id
  static String landmarkShow(int id) => 'landmark/show/$id';

  //*********** landmark/myLandMarks page
  static String myLandMarks(int page) => 'landmark/myLandMarks?page=$page';

  //*********** favorites
  static const String addFavorites = 'favorites';

  //*********** saved
  static const String saved = 'saved';
  //update-guide-photo
  static const String updateGuidePhoto = 'update-guide-photo';
  //languages
  static const String languages = 'languages';
  //tourGuides
  static const String tourGuides = 'get-guide';
  //region
  static const String region = 'regions';
  //storeGuide
  static const String storeGuide = 'store-guide';

  //saveTrip
  static const String saveTrip = 'trips/store';
  //https://dashboard.hawdaj.net/api/my-trips?page=1
  static String myTrip(int page) => 'my-trips?page=$page';
  static String newMyTrip(int page) => 'v2/trips/my-trips?page=$page';
  //*********** saveTripToEmail
  static const String saveTripToEmail = 'trips/save-trip-to-email';

  //finishTrip
  static String finishTrip(String token) => 'trips/prepare/$token';
  //swalefs/bedouin-and-alive-wafa-al-remal

  static String details(String slug) => 'swalefs/$slug';
  //offers
  static String offers(int id) => 'zads/$id/offers';
  //menu
  static String menu(int id) => 'zads/$id/menus?page=1&perPage=10';
  //detailsEvents
  static String detailsEvents(String slug) => 'events/$slug';

  //detailsRestaurants
  static String detailsRestaurants(String slug) => 'zads/$slug';
  //detailsTourGuide
  static String detailsTourGuide(int id) => 'guides/$id';
  //tourGuideByTopRated
  static String tourGuideByTopRated() =>
      'guides?page=1&per_page=4&excludedId=11&top_rated=true';

  //*********** places
  static const String places = 'places';
  static String detailsPlaces(String slug) => 'places/$slug';
  //embroidered
  //heritage-identity

  static String detailsStores(String slug) => 'stores/$slug';

  //*********** stores
  static const String stores = 'stores';

  //*********** zads
  static const String zads = 'zads';

  //*********** events
  static const String events = 'events';

  //*********** stories (swalefs)
  static const String stories = 'swalefs';
  static const String myLastDayStories = 'get-profile-page';
  //stories/myLastDayStories
  static const String storiesmyLastDayStories = "stories/myLastDayStories";
  static const String addStory = 'stories/store';

  //*********** applications (apps)
  static const String applications = 'applications';

  //*********** guides
  static const String guides = 'guides';

  //*********** landmarks
  static const String landmarks = 'landmarks';
  static const String landmarkStore = 'landmark/store';

  // Auth endpoints
  static const String register = 'register';
  static const String login = 'login';
  static const String logout = 'logout';
  static const String googleLogin = 'social/callback';
  static const String appleLogin = 'social/callback';
  static const String twitterLogin = 'social/callback';
  static const String deleteProfile = 'delete-profile';

  static const String updateProfile = 'update-profile';
  //update-photo
  static const String updatePhoto = 'update-photo';
  //*********** trips
  static const String prices = 'prices';
  static const String prepare = 'trips/prepare';
  // *********** v2/trips/prepare
  static const String prepareV2 = 'v2/trips/prepare';
  static const String categoriesPlaces = 'categories';
  //*********** categories for different types
  static const String storesCategories = 'stores/categories';
  static const String zadsCategories = 'zads/categories';
  static const String zadsFoodCategories = 'zads/food-categories';
  static const String swalefsCategories = 'swalefs-categories';
  static const String applicationsCategories = 'applications/categories';
  static const String myTrips = 'my-trips?page=1&per_page=8';
  static String viewTrip(String tripId) => 'view_trip/$tripId';
  static String deleteTrip(String tripId) => 'v2/trips/$tripId';
  // 'delete_trip/$tripId';

  static const String tripList = 'trips?page=1&per_page=8';
  static const String regions = 'regions';
  static String citiesByRegion(int regionId) => 'regions/$regionId/cities';

  static const String forgotPassword = 'forgot-password';
  static const String verifyEmailCode = 'verify-email-code';
  //stories/:id/delete
  static String deleteStory(int storyId) => 'stories/$storyId/delete';
  //favorites
  static const String favorites = 'get-my-favorites';
  //get-my-saved
  static const String mySaved = 'get-my-saved';
  //showPrepareTrip
  static String showPrepareTrip(String token) => 'v2/trips/prepare/$token';
  //properties
  static const String properties = 'properties';

  //update location
  static const String updateLocation = 'update-location';
}
