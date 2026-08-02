import { ProfileSocialPlatform } from '../../../profile-settings/interfaces/profile-settings.interface';

export interface IProfilePageResponseDto {
    code: number;
    message: string;
    data: IProfilePageDataDto;
}

export interface IProfilePageDataDto {
    PersonalData: IPersonalDataDto | null;
    userGuideData: IUserGuideDataDto | null;
    social: IProfileSocialRecordDto[];
    hasSocial: boolean;
    myStories: IPaginatedItemsDto<IStorySummaryDto>;
    myTrips: IPaginatedItemsDto<ITripSummaryDto>;
    totalStories: number;
    totalTrips: number;
}

export interface IPersonalDataDto {
    id: number;
    first_name: string | null;
    last_name: string | null;
    email: string | null;
    phone: string | null;
    photo: string | null;
    gender: 'male' | 'female' | string | null;
    full_name: string | null;
    total_points: number;
}

export interface IUserGuideDataDto {
    id: number;
    type: string | null;
    name: string | null;
    nickName: string | null;
    description: string | null;
    image: string | null;
    show_in_home: boolean;
    experience: number;
    gender: 'male' | 'female' | string | null;
    regions: IRegionSummaryDto[];
    languages: ILanguageSummaryDto[];
    social: Partial<Record<ProfileSocialPlatform, string | null>>;
    rate: number;
    ratings: unknown[];
    galleries: unknown[];
}

export type IProfileSocialRecordDto = {
    id: number;
    user_id: number;
    created_at: string;
    updated_at: string;
} & Partial<Record<ProfileSocialPlatform, string | null>>;

export interface IStorySummaryDto {
    id?: number;
    name: string | null;
    token: string | null;
    date: string | null;
    created_at: string | null;
}

export interface ITripSummaryDto {
    name: string | null;
    item_per_day: string | null;
    days: string | null;
    items: string | null;
    date: string | null;
    user_id: number | null;
    created_at: string | null;
    email: string | null;
    token: string | null;
    start_date: string | null;
    end_date: string | null;
    region1Object: IRegionSummaryDto | null;
    region2Object: IRegionSummaryDto | null;
}

export interface IRegionSummaryDto {
    id: number;
    name: string;
}

export interface ILanguageSummaryDto {
    id: number;
    name: string;
}

export interface IPaginatedItemsDto<T> {
    current_page: number;
    items: T[];
    first_page_url: string | null;
    from: number | null;
    last_page: number;
    last_page_url: string | null;
    next_page_url: string | null;
    path: string | null;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
}



