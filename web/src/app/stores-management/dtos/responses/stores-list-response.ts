import { PaginationListingResponse } from "src/app/Common/core";
import { IStoreItem } from "../../models";

export interface IPlace extends IStoreItem {
}

export interface IStoresListApiResponse extends PaginationListingResponse<IPlace> { }