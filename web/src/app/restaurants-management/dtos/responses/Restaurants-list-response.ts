import { PaginationListingResponse } from "src/app/Common/core";
import { IRestaurantItem } from "../../models";

export interface IRestaurant extends IRestaurantItem {
}

export interface IRestaurantsListApiResponse extends PaginationListingResponse<IRestaurant> { }