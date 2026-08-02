export const PlacesEndpoints = {
    base: 'places',
    getAll: 'places',
    getById: (id: number) => `places/${id}`,
    relatedPlaces: (id: number) => `places/${id}/related-places`,
    categories: 'places/categories',
    subCategories: (categoryId: number) => `places/categories/${categoryId}/sub-categories`,
    prices: 'places/prices',
    heroSlider: 'places/hero-slider',
    placeNames: 'places/names',
    tripSteps: 'places/trip-steps',
    savePlace: 'places/favorite',
};
