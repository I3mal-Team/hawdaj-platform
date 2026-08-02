/* ---------- Trip Day Detail Interfaces ---------- */

/**
 * Place card data interface
 */
export interface IPlaceCard {
    /** Place ID */
    id: string;

    /** Place slug for URL */
    slug: string;

    /** Place title */
    title: string;

    /** Place description */
    description: string;

    /** Place image URL */
    imageUrl: string;

    /** Place type (e.g., 'place', 'store', 'restaurant', 'event') */
    type: string;

    /** Place location */
    location: string;

    /** Latitude */
    latitude?: number;

    /** Longitude */
    longitude?: number;

    /** Rating value */
    rating?: number;

    /** Number of reviews */
    reviewsCount?: number;
}

/**
 * Time period data interface (Morning/Evening)
 */
export interface ITimePeriodData {
    /** Period type: 'morning' or 'evening' */
    type: 'morning' | 'evening';

    /** Period title */
    title: string;

    /** Period description */
    description: string;

    /** Places to visit during this period */
    places: IPlaceCard[];

    /** Icon name for the period */
    icon?: string;
}

/**
 * Trip day data interface
 */
export interface ITripDayData {
    /** Day number */
    dayNumber: number;

    /** Day title (e.g., "يومك الأول") */
    dayTitle: string;

    /** Date in format "12 مارس 2025" */
    date: string;

    /** Number of places to visit */
    placesCount: number;

    /** Region/City name */
    regionName: string;

    /** Region description */
    regionDescription: string;

    /** Optional city info (for raw API data) */
    cityName?: string;
    cityDescription?: string;
    city?: {
        name?: string;
        description?: string;
    };

    /** Morning activities */
    morning?: ITimePeriodData;

    /** Evening activities */
    evening?: ITimePeriodData;
}

/**
 * Trip day detail configuration
 */
export interface ITripDayConfig {
    /** Show place cards */
    showPlaces?: boolean;

    /** Show region info */
    showRegionInfo?: boolean;

    /** RTL layout */
    isRtl?: boolean;

    /** Custom CSS class */
    customClass?: string;
}


