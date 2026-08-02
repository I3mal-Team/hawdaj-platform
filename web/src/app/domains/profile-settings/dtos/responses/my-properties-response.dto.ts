export interface IMyPropertiesResponseDto {
  code: number;
  message: string;
  data: {
    places: IPropertyTypeData;
    stores: IPropertyTypeData;
    events: IPropertyTypeData;
    zad_elgadels: IPropertyTypeData;
  };
}

export interface IPropertyTypeData {
  current_page: number;
  items: IPropertyItem[];
  first_page_url: string;
  from: number | null;
  last_page: number;
  last_page_url: string;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number | null;
  total: number;
}

export interface IPropertyItem {
  id: number;
  slug: string;
  type: 'place' | 'store' | 'event' | 'zad';
  title: string;
  description: string;
  address: string;
  image: string;
  cover_image: string;
  lat: number;
  long: number;
  city: {
    id: number;
    name: string;
  };
  region: {
    id: number;
    name: string;
  };
  categories: Array<{
    id: number;
    name: string;
    icon?: string;
  }>;
  price?: {
    id: number;
    name: string;
  };
  seasons?: string[];
  status: 'pending' | 'accepted' | 'rejected';
  rejected_reason?: string | null;
  active: number;
  views_num: number;
  featured: boolean;
  rate: number;
  review: number;
  is_favorite: boolean;
  is_saved: boolean;
  ownership_proof_file?: string;
  // Social media links
  whatsapp?: string;
  facebook_link?: string;
  instagram_link?: string;
  website_link?: string;
  // Event specific
  ticket_link?: string | null;
  date_from?: string | null;
  date_to?: string | null;
  // Zad specific
  food_categories?: Array<{
    id: number;
    name: string;
    icon?: string;
  }>;
  menu_file?: string;
}

export interface IMyPropertyDetailsResponseDto {
  code: number;
  message: string;
  data: IPropertyItem | null;
}
