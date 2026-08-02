export interface item {
    title: string;
    address: string;
    address_name: string;
    image: string;
    is_favorite: boolean;
    rate: number;
    ratings: any[];
    slug: string;
    startTime?: string;
    endTime?: string;
    name?: string;
    is_online?: number;
    website_link?: string;
    foodCategory?: string;
    date_from?: string;
    date_to?: string;
    isLoadingFavourite?: boolean;
    description?: string
}