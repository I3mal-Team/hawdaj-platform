export interface ITripsResponseDto {
  code: number;
  message: string;
  data: {
    trips: ITripItem[];
    pagination: {
      current_page: number;
      last_page: number;
      per_page: number;
      total: number;
    };
  };
}

export interface ITripItem {
  /** Trip identifiers */
  id: number;
  token: string;
  
  /** Trip basic info */
  name: string;
  total_days: string;
  places_per_day: string;
  places_per_period: number;
  total_places: number;

  /** Trip date & time */
  start_date: string;
  end_date: string;
  startTime?: string;
  endTime?: string;

  /** Regions */
  start_region: IRegion;
  end_region: IRegion;

  /** Metadata */
  created_at: string;

  /** Legacy fields for backward compatibility */
  image?: string;
  item_per_day?: string;
  days?: string;
  items?: number[][];
  date?: string;
  user_id?: number;
  email?: string | null;
  region1Object?: any | null;
  region2Object?: any | null;
}


export interface ITripDetailsResponseDto {
  code: number;
  message: string;
  data: IEnhancedTripItem;
}

export interface IEnhancedTripItem {
  token: string;
  start_date: string;
  end_date: string;
  total_days: number;
  places_per_day: string;
  places_per_period: number;
  start_region: IRegion;
  end_region: IRegion;
  enhanced_data?: IEnhancedDay[];
  days?: IEnhancedDay[]; // New format support
  user_id?: number;
  id?: number;
  name?: string;
  total_places?: number;
  created_at?: string;
}

export interface IRegion {
  id: number;
  name: string;
}

export interface IEnhancedDay {
  day_number: number;
  date: string;
  morning: IEnhancedPeriod;
  evening: IEnhancedPeriod;
  total_places_count?: number;
}

export interface IEnhancedPeriod {
  places: IEnhancedPlace[];
  description: string;
  places_count?: number;
}

export interface IEnhancedPlace {
  id: number;
  slug?: string;
  name?: string | null; // New format support
  categories?: number[];
  address?: string | null;
  image?: string;
  city_id?: string;
  region_id?: string;
  city?: string | ICity; // Support both string and object
  region?: string | IRegion; // Support both string and object
  ticket_link?: string | null;
  website_link?: string | null;
  instagram_link?: string | null;
  whatsapp?: string | null;
  facebook_link?: string | null;
  temperature?: string | null;
  seasons?: string[];
  price_id?: string;
  address_type?: string;
  active?: number;
  deleted_at?: string | null;
  created_at?: string;
  updated_at?: string;
  views_num?: number;
  related_places?: string[] | null;
  near_stores?: string | null;
  featured?: boolean;
  lat: number;
  long: number;
  visited?: boolean;
  distance?: number;
  key_words?: (string | null)[];
  prefered?: number;
  place_icon?: string | null;
  user_id?: number | null;
  show_in_home?: number;
  rate?: number;
  review?: number;
  type?: string;
  title?: string;
  description?: string;
  translations?: IPlaceTranslation[];
  ratings?: any[];
}

export interface IPlaceTranslation {
  id: number;
  place_id: number;
  locale: string;
  title: string;
  description: string;
}

export interface ICity {
  id: number;
  region_id: number;
  created_at?: string;
  updated_at?: string;
  deleted_at?: string | null;
  name: string;
  translations?: ICityTranslation[];
}

export interface ICityTranslation {
  id: number;
  city_id: number;
  locale: string;
  name: string;
}

export interface IMapLocation {
  lat: number;
  lng: number;
  name?: string;
  title: string;
  image?: string;
  address_name?: string;
  review?: number;
  type?: string;
  rate?: number;
  slug?: string;
}

/** Prepare Trip Response DTO */
export interface IPrepareTripResponseDto {
  code: number;
  message: string;
  data: IEnhancedTripItem;
}

/** Save Trip Response DTO */
export interface ISaveTripResponseDto {
  code: number;
  message: string;
  data: IEnhancedTripItem & {
    id: number;
    total_places: number;
  };
}

/** Re-prepare Trip Response DTO */
export interface IReprepareTripResponseDto {
  code: number;
  message: string;
  data: IEnhancedTripItem;
}