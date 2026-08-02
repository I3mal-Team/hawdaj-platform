export interface ICreatePropertyRequestDto {
  type: string;
  title: string;
  description: string;
  categories: string[];
  lat: string;
  long: string;
  address?: string;
  whatsapp?: string;
  facebook_link?: string;
  instagram_link?: string;
  website_link?: string;
  region_id: string;
  city_id: string;
  ownership_proof_file?: File;
  image?: File;
  menu_file?: File;
  food_categories?: string[];
  best_seasons?: string[];
  prices?: string[];
  video_link?: string;
  ticket_link?: string;
  date_from?: string;
  date_to?: string;

  // Store specific fields
  con_type?: string;
  address_type?: string;
}









