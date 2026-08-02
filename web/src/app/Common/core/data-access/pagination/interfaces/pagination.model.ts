export interface IPaginationParams {
    page?: number;
    perPage?: number;
    search?: string;
    placeName?: string;
    regionId?: number;
    region_id?: number;
    cityId?: number;
    city_id?: number;
    categoryId?: number;
    category_id?: number;
    subCategoryId?: number;
    priceId?: number;
    top_visited?: boolean;
    top_featured?: boolean;
    per_page?: number;
    is_online?: number;

    // ZAD
    topRestaurantsPage?: number;
    topRestaurantsPerPage?: number;
    region?: number;
    city?: number;
    lat?: number;
    lng?: number;
    restaurantCategoriesIds?: number[];
    categories?: number[];
    "food_categories[]"?: number;
    selectedScore?: number;
    keyword?: string;
    showNearest?: boolean;
    rate?: number;
    params?: any;
    top_stores?: boolean


    //events
    daterange?: string;
    address_type?: string;

    //tourGuides
    language_id?: number[];
    experience?: number;
    top_rated?: boolean;
    excludedId?: number;
}
