import { PaginationMetadata } from "./pagination-metadata.interface";

export interface PaginationListingResponse<TEntity> {
    code: number;
    message: string;
    data: PaginationData<TEntity>;  // Pass TEntity to PaginationData
}

interface PaginationData<TEntity> extends PaginationMetadata {
    items: TEntity[];  // Use TEntity instead of Place
}

export interface PaginationDetailsResponse<TEntity> {
    code: number;
    message: string;
    data: TEntity;  // Pass TEntity to PaginationData
}
