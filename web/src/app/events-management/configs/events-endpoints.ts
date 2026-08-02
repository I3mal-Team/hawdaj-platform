export const EventsEndpoints = {
    base: 'events',
    getAll: 'events',
    getById: (id: number) => `events/${id}`,
    relatedPlaces: (id: number) => `events/${id}/related-events`,
    sendFeedbackFromEvent: "rates",
    categories: 'places/categories',
    subCategories: (categoryId: number) => `places/categories/${categoryId}/sub-categories`,
    prices: 'places/prices',
    heroSlider: 'places/hero-slider',
    placeNames: 'places/names',
    tripSteps: 'places/trip-steps',
    savePlace: 'places/favorite',
};
