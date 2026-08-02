export interface IHomeApiResponse {
  code: number;
  message: string;
  data: IHome
}
export interface IHome {
  applications: IApplications,
  places: IPlace,
  topEvents: IEvent,
  topVisitedPlaces: ITopVisitedPlaces,
  topVisitedStores: IStore,
  guides: IGuide,
  zads: IZads,
  services: IServiceItem[],
  globalMapData: IGlobalMapItem[]
  swalefs: any;
}

interface IGuide {
  current_page: number;
  items: IGuideItem[]; // Updated to reflect the structure of items in the response
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}

export interface IGuideItem {
  id: number;
  name: string;
  nickName: string;
  description: string;
  image: string;
  show_in_home: boolean;
  experience: number | any;
  phone?: any;
  regions: Region[]; // Array of regions
  languages: Language[]; // Array of languages
  social: Social; // Social media links
}

export interface TourGuideFormDataType {
  name: string;
  nickName: string;
  description: string;
  experience: number;
  regions: number[];  // For region IDs
  languages: string[]; // For language IDs (assuming they are strings like 'ar', 'en')
  facebook?: string;
  twitter?: string;
  instagram?: string;
  linkedin?: string;
  personal_account?: string;
}


interface Region {
  id: number;
  name: string;
}

interface Language {
  id: number;
  name: string;
}

interface Social {
  youtube?: string;
  facebook?: string;
  twitter?: string;
  instagram?: string;
  linkedin?: string;
  personal_account?: string;
}

export interface IApplications {
  current_page: number,
  items: IAppItem[],
  first_page_url: string,
  from: null,
  last_page: number,
  last_page_url: string,
  next_page_url: null,
  path: string,
  per_page: number,
  prev_page_url: null,
  to: null,
  total: number
}
export interface IAppItem {
  id: number,
  slug: string,
  type: string,
  link: string,
  image: string,
  ios_link: string | null,
  android_link: string | null,
  title: string,
  description: string,
  active: number
}

export interface IGlobalMapItem {
  country_code: string | null,
  count: number | null,
  name: string | null
}

export interface IPlace {
  current_page: number,
  items: IPlaceItem[],
  first_page_url: string,
  from: number,
  last_page: number,
  last_page_url: string,
  next_page_url: string,
  path: string,
  per_page: number,
  prev_page_url: null,
  to: number,
  total: number
}
export interface IPlaceItem {
  id: 17,
  slug: string,
  type: string,
  categories: ICategory[],
  address: string,
  address_name?: string | null;
  image: string,
  ticket_link: string | null,
  website_link: string | null,
  instagram_link: string | null,
  whatsapp: string | null,
  facebook_link: string | null,
  temperature: number,
  seasons: [
    string
  ],
  related_places: [],
  galleries: IGallery[],
  price: {
    id: number,
    name: string | null
  },
  city: ICity,
  region: IRegion,
  address_type: string | null,
  active: number,
  views_num: number,
  near_stores: string | null,
  featured: boolean,
  lat: number,
  long: number,
  visited: true,
  distance: string | null,
  key_words: string | null,
  prefered: number,
  place_icon: string | null,
  rate: number,
  review: number,
  title: string,
  description: string,
  ratings: [],
  meta: string | null,
  is_favorite: false
}
interface ICategory {
  id: number,
  icon: string,
  name: string
}
interface IGallery {
  id: number,
  file: string,
  type: string
}

interface IEvent {
  current_page: number;
  items: IEventItem[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}
interface IEventItem {
  id: number;
  slug: string;
  type: string;
  date_from: string | null;
  date_to: string | null;
  closed: boolean;
  ticket_link: string | null;
  image: string;
  facebook: string | null;
  whatsapp: string | null;
  instagram: string | null;
  website: string | null;
  display_type: string | null;
  address: string | null;
  video_url: string;
  link: string | null;
  lat: number;
  long: number;
  views_num: number;
  featured: number;
  visited: number;
  active: number;
  related: any | null;
  address_type: string;
  title: string;
  description: string;
  ratings: IRating[];
  rate: number;
  galleries: IGallery[];
  city: ICity | null;
  region: IRegion | null;
  meta: any | null;
  is_favorite: boolean;
}
interface IRating {
  id: number;
  name: string;
  email: string;
  rateText: string;
  rate: string;
  type: string;
  parent_id: number;
  created_at: string;
  updated_at: string;
  user_id: number | null;
}


interface ITopVisitedPlaces {
  current_page: number;
  items: ITopVisitedPlaceItem[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}
interface ITopVisitedPlaceItem {
  id: number;
  slug: string;
  type: string;
  categories: ICategory[];
  address: string;
  image: string;
  ticket_link: string | null;
  website_link: string | null;
  instagram_link: string | null;
  whatsapp: string | null;
  facebook_link: string | null;
  temperature: string | null;
  seasons: string[];
  related_places: any[]; // Change type accordingly
  galleries: IGallery[];
  price: {
    id: number;
    name: string;
  };
  city: {
    id: number;
    name: string;
  };
  region: {
    id: number;
    name: string;
  };
  address_type: string;
  active: number;
  views_num: number;
  near_stores: any; // Change type accordingly
  featured: boolean;
  lat: number;
  long: number;
  visited: boolean;
  distance: string;
  key_words: any; // Change type accordingly
  prefered: number;
  place_icon: string;
  rate: number;
  review: number;
  title: string;
  description: string;
  ratings: any[]; // Change type accordingly
  meta: any | null;
  is_favorite: boolean;
}

export interface IStoreItem {
  id: number;
  slug: string;
  type: string;
  categories: any[];
  address: string | null;
  image: string;
  facebook_link: string | null;
  whatsapp: string | null;
  instagram_link: string | null;
  website_link: string | null;
  active: number;
  address_type: string;
  views_num: number;
  featured: boolean;
  visited: boolean;
  distance: string | null;
  related_places: any[];
  near_places: any[];
  lat: number;
  long: number;
  key_words: any | null;
  is_online: boolean;
  rate: number;
  review: number;
  title: string;
  description: string;
  address_name?: string | null;
  ratings: IRating[];
  galleries: IGallery[];
  city: ICity | null;
  region: IRegion | null;
  meta: any | null;
  is_favorite: boolean;
}
interface ICity {
  id: number;
  name: string;
}
interface IRegion {
  id: number;
  name: string;
}
interface IStore {
  current_page: number;
  items: IStoreItem[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}

interface IZads {
  current_page: number,
  items: IZadItem[],
  first_page_url: string | null,
  from: number,
  last_page: number,
  last_page_url: string | null,
  next_page_url: string | null,
  path: string | null,
  per_page: number,
  prev_page_url: null,
  to: number,
  total: number
}
interface IZadItem {
  id: number,
  slug: string | null,
  type: string | null,
  categories: ICategory[],
  food_categories: [],
  address: string | null,
  image: string,
  menu_file: string,
  facebook_link: string | null,
  whatsapp: string | null,
  related_places: [],
  Instagram_link: string,
  website_link: string | null,
  lat: number,
  long: number,
  active: number,
  address_type: string,
  featured: boolean,
  visited: boolean,
  views_num: number,
  rate: number,
  review: number,
  title: string,
  description: string,
  ratings: IRating[],
  galleries: IGallery[],
  city: ICity,
  region: IRegion,
  meta: {
    title: string | null,
    link: string | null,
    keyWords: string | null
  },
  is_favorite: boolean
}
interface IServiceItem {
  id: number,
  value: string | null
}
