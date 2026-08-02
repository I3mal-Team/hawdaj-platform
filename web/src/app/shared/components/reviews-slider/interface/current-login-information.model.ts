export interface ICurrentLoginInformation {
    userId: number;
    username: string;
    email: string;
    profileImage?: string;
    role: string;
    token?: string;
    PersonalData?: {
        id: number;
        photo?: string;
    };
}
