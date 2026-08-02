export interface IApiListResponseDto<T> {
  code: number;
  message: string;
  data: T[];
}

export interface IPriceItem {
  id: number;
  name: string;
}

export interface ICityItem {
  id: number;
  name: string;
}

export interface IRegionItem {
  id: number;
  name: string;
}

export interface ICategoryItem {
  id: number;
  name: string;
  icon: string;
}

export interface IFoodCategoryItem {
  id: number;
  name: string;
  icon: string;
}

export type IPricesResponseDto = IApiListResponseDto<IPriceItem>;
export type ICitiesResponseDto = IApiListResponseDto<ICityItem>;
export type IRegionsResponseDto = IApiListResponseDto<IRegionItem>;
export type ICategoriesResponseDto = IApiListResponseDto<ICategoryItem>;
export type IFoodCategoriesResponseDto = IApiListResponseDto<IFoodCategoryItem>;










