export interface ITripsRequestDto {
}

/**
 * Prepare Trip Request DTO
 * Used for creating/preparing a new trip
 */
export interface IPrepareTripRequestDto {
  start_date: string;
  end_date: string;
  start_region_id: string | number;
  end_region_id: string | number;
  places_per_day: string | number;
  categories: (string | number)[];
  price_range: (string | number)[];
  vehicleType: 'car' | 'air' | string;
}

/**
 * Save Trip Request DTO
 * Used for saving a prepared trip
 */
export interface ISaveTripRequestDto {
  name: string;
  prepare_token: string;
}

/**
 * Re-prepare Trip Request DTO
 */
export interface IReprepareTripRequestDto {
  prepare_token: string;
}
