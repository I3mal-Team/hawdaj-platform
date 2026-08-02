import { PaginationListingResponse } from "src/app/Common/core";
import { IStoryItem } from "../../models";

export interface IStory extends IStoryItem {
}

export interface IStoriesListApiResponse extends PaginationListingResponse<IStory> { }