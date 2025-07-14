export interface Visit {
    custom_id: string;
    visit_id: string;
    staff_id: string;
    client: Client;
    services: Service[];
    start_date: string;
    end_date: string;
    start_hours: string;
    end_hours: string;
    height: number;
    top: number;
    nesting: number;
    status: VisitStatus;
}

export interface Client {
    id: string;
    name: string;
    phones: string[];
}

export interface Service {
    id: string;
    sum: number;
    title: string;
}

export enum VisitStatus {
    Archival = 'Archival',
    NotCame = 'NotCame',
    Expected = 'Expected',
    Came = 'Came',
    Finished = 'Finished',
    Canceled = 'Canceled',
}
