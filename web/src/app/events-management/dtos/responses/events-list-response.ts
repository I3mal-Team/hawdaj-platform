import { PaginationListingResponse } from "src/app/Common/core";
import { IEventItem } from "../../models";

export interface IEvent extends IEventItem {
}

export interface IEventsListApiResponse extends PaginationListingResponse<IEvent> { }