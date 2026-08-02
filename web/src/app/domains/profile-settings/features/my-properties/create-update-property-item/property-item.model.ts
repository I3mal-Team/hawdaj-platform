import { PropertyItemType } from './property-item-type.enum';

export interface DepartmentOption {
  id: number;
  name: string;
  icon?: string;
}

export interface PropertyUploadFile {
  id: string;
  file: File;
  name: string;
  size: number;
  previewUrl?: string;
  status: 'pending' | 'uploaded';
}

export interface SocialMediaPlatform {
  id: string;
  name: string;
  icon?: string;
}

export interface SocialMediaItem {
  id: string;
  platform: SocialMediaPlatform;
  link: string;
}

import { SeasonType } from './season-type.enum';

export interface SeasonOption {
  id: SeasonType;
  name: string;
}

export interface PriceOption {
  id: number;
  name: string;
}

export interface FoodTypeOption {
  id: number;
  name: string;
  icon?: string;
}

export interface CityOption {
  id: number;
  name: string;
}

export interface RegionOption {
  id: number;
  name: string;
}

export interface CategoryOption {
  id: number;
  name: string;
  icon?: string;
}

export interface LocationData {
  lat: number;
  lng: number;
  address?: string;
  name?: string;
}

export interface PropertyItemFormValue {
  propertyType: PropertyItemType | null;
  departments: DepartmentOption[];
  propertyName: string;
  propertyAddress: string;
  description: string;
  city: CityOption | null;
  region: RegionOption | null;
  location?: LocationData;
  ownershipFile: PropertyUploadFile | null;
  propertyImages: PropertyUploadFile[];
  socialMedia: SocialMediaItem[];
  // Conditional fields
  bestSeasons?: SeasonOption[];
  prices?: PriceOption[];
  foodTypes?: FoodTypeOption[];
  menuImage?: PropertyUploadFile | null;
  videoLink?: string;
  ticketLink?: string;
  startDate?: Date | null;
  endDate?: Date | null;
  storeType?: StoreTypeOption | null;
}

// Sotre Types
export enum StoreType {
  Online = 'online',
  Offline = 'local'
}

export interface StoreTypeOption {
  id: StoreType;
  name: string; // مفتاح الترجمة
}

export interface StoreAddressTypeOption {
  id: StoreAddressType;
  name: string;
}

export enum StoreAddressType {
  Map = 'map',
  Link = 'link'
}






