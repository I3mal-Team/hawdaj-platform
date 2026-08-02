
export interface IStoryItem {
    id: number;
    slug: string;
    type: string;
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
    meta: any | null;
    is_favorite: boolean;
    is_saved: boolean;
}

