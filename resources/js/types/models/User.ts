export interface User {

    id?: number;
    uuid: string;
    name: string;
    email: string;
    roles?: {
        id: number,
        name: string,
        description: string,
        icon: string,
    }[],

}