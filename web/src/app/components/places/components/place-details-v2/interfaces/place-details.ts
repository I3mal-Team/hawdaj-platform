export interface Place {
    code: number;
    data: data;
    message: string;

}
export interface data {
    items: items;
    id: number;
}
export interface items {
    id: number;
    title: string;
    type: string;
    active: number;
    address: string;
    address_name: string;
    address_type: string;
    city: {
        id: number;
        name: string;
    };
    region: {
        id: number;
        name: string;
    };
    cover_image: string;
    image: string;
    description: string;
    distance: number | null;
    featured: boolean;
    is_favorite: boolean;
    is_saved: boolean;
    key_words: string | null;
    lat: number;
    long: number;
    meta: {
        title: string;
        description: string;
        link: string | null;
        keyWords: string | null;
    };
    near_stores: any | null;
    place_icon: string | null;
    prefered: number;
    price: {
        id: number;
        name: string;
    };
    rate: number;
    ratings: any[];
    related_places: any[];
    review: number;
    seasons: string[];
    slug: string;
    temperature: number | null;
    ticket_link: string | null;
    views_num: number;
    visited: boolean;
    website_link: string | null;
    whatsapp: string | null;
    facebook_link: string | null;
    instagram_link: string | null;
    categories: any[];
    galleries: any[];
}