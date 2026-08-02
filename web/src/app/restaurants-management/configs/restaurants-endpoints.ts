export const RestaurantsEndpoints = {
    base: 'zads',
    getAll: 'Restaurants',
    getBySlug: (slug: string) => `zads/${slug}`,
    categories: 'Restaurants/categories',
    subCategories: (categoryId: number) => `Restaurants/categories/${categoryId}/sub-categories`,
    regions: 'regions',
};
