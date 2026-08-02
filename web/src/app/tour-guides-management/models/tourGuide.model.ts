import { ICategoryItem } from "./category.model";
import { IGalleryItem } from "./gallery.model";
import { IPricesItem } from "./prices.model";
import { IRatingItem } from "./rating.model";

export interface ITourGuideItem {
    id: number;
    slug: string;
    type: string;
    categories: ICategoryItem[];
    address: string;
    image: string;
    cover_image: string;
    ticket_link: string | null;
    website_link: string | null;
    instagram_link: string | null;
    whatsapp: string | null;
    facebook_link: string | null;
    temperature: number;
    seasons: string[];
    related_places: any[];
    galleries: IGalleryItem[];
    price: IPricesItem[];
    city: Location;
    region: Location;
    address_type: string;
    active: number;
    views_num: number;
    near_stores: any | null;
    featured: boolean;
    lat: number;
    long: number;
    visited: boolean;
    distance: string;
    key_words: string | null;
    prefered: number;
    place_icon: string;
    rate: number;
    review: number;
    title: string;
    description: string;
    ratings: IRatingItem[];
    meta: any | null;
    is_favorite: boolean;
    is_saved: boolean;
}

