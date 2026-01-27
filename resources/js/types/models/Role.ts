export interface Role {
    id: number;
    name: string;
    description: string,
    icon: string,
    permissions?: {
        id: number;
        name: string;
    }[],
    
}