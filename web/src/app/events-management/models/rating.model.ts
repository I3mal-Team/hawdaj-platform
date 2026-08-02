export interface IRatingItem {
    id: number;
    name: string;
    email: string;
    rateText: string;
    rate: string;
    type: string;
    parent_id: number;
    created_at: string;
    updated_at: string;
    user_id: number | null;
}