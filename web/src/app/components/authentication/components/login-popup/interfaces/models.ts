
// Define the UserData interface
export interface UserData {
    id: string;
    name: string;
    email: string;
    role: string;
}

// Define the AuthResponse interface for API response on login
export interface AuthResponse {
    code: number;
    message: string;
    data: UserData; // User data after login
}
