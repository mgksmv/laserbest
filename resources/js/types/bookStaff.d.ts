import { Visit } from '@/types/visit';

export interface BookStaff {
    id: string;
    name: string;
    avatar: string;
    upcoming_dates: string[];
    upcoming_times: string[];
    visits?: Visit[];
}
