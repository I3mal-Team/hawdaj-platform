export const roots = {
  isFavorite: 'favorites',
  isSaved: 'saved',
  apps: {
    getAll: 'applications',
    getCategories: 'applications/categories',
  },
  tourGuides: {
    getAll: 'guides',
    updateTourGuideProfile: '/store-guide',
    getTourGuideProfile: '/get-guide',
    updatePhoto: 'update-guide-photo',
    sendFeedbackFromTourGuide: 'rates'
  },
  auth: {
    login: '/login',
    register: "/register",
    logout: "/logout",
    forgetPassword: "/forgot-password",
    validateResetCode: '/verify-email-code',
    resetPassword: '/reset-password',
    changePassword: '/update-password',
    updateProfile: '/update-profile',
    profileData: '/get-profile-page',
    uploadFile: '/uploadFile',
  },
  profile: {
    addLandmark: "landmark/store",
    uploadStory: "stories/store",
    updatePhoto: 'update-photo',
    addNewStory: 'stories/store',
    addCommentToStory: 'stories/addCommentToStory',
    myLastDayStories: 'stories/myLastDayStories',
  },
  home: {
    home: 'home',
    getPlaces: 'places',
    getGlobalSearch: 'global-search',
    getEvents: 'get_events',
    getTravelers: "get_travelers",
    getSaudiOpening: "get_saudi_opening",
    getSaudiPlaces: "getSaudiPlaces",
    getServices: "services",
    getTopDestinations: 'get_top_destinations',
    subscribe: 'subscribe',
    sendEmail: "send_email",
    leaveInquiry: "contactus/send",
    placesDataMap: "places/places-data-for-map",
    getGlobalMapData: "global-map-data",
    getSearchResults: "getSearchResults",
    addToFavorite: "addToFavorite",
    favorites: "favorites"
  },
  places: {
    getCategories: 'categories',
    getSubCategories: 'sub-categories',
    getRegions: 'regions',
    getPrices: 'prices',
    getLanguages: 'languages',

    getHeroSliderPlaces: 'get_sliderPlaces',
    getPlacesNames: 'getPlacesNames',
    getCities: 'getCities',
    searchNow: 'searchNow',
    getTripSteps: "getTripSteps",
    sendFeedbackFromPlaces: "rates",
    getChatGpt: "getChatGpt",
    addChatGpt: "addChatGpt",
  },
  trips: {
    prepareTrip: "trips/prepare",
    saveTrip: 'trips/store',
    sendTripEmail: 'trips/save-trip-to-email',
    getTripForSave: 'getTripForSave',
    regenerateTrip: 'regenerateTrip',
    getSuggestedPlaces: 'getSuggestedPlaces',
    addPlaces: 'addPlaces',
    getAllTrips: "my-trips",
    deleteTrip: "delete_trip",
    viewTrip: "view_trip"
  },
  events: {
    getAllEvents: 'events',
    rate: 'rates'
  },
  stores: {
    getStoresCategories: 'stores/categories',
    getStores: 'stores',
    getTopStores: 'stores',
    getStoreById: 'stores',
    sendFeedbackFromStore: "rates",
    getChatGpt: "getChatGpt",
    addChatGpt: "addChatGpt",
  },
  restaurants: {
    zads: 'zads',
    getTopCategories: "getTopCategories",
    getRestaurantMenu: 'getRestaurantMenu',
    getRestaurantTypes: 'getRestaurantTypes',
    getCuisines: 'getCuisines'
  },
  stories: {
    getStories: "swalefs",
    getCategories: "getCategories",
    getReviews: "getReviews"
  }
}
