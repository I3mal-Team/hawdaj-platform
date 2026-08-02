import { PaginationListingResponse } from "src/app/Common/core";
import { ITourGuideItem } from "../../models";

export interface ITourGuide extends ITourGuideItem {
}

export interface ITourGuideListApiResponse extends PaginationListingResponse<ITourGuide> { }