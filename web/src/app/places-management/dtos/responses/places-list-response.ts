import { PaginationListingResponse } from "src/app/Common/core";
import { IPlaceItem } from "../../models";

export interface IPlace extends IPlaceItem {
}

export interface IPlacesListApiResponse extends PaginationListingResponse<IPlace> { }