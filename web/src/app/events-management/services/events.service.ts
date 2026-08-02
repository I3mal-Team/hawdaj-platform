import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from '../../Common/core/data-access/http-client/clients/api.service';
import { EventsEndpoints } from '../configs/events-endpoints';
import { IFeedbackData, IPaginationParams } from 'src/app/Common/core';
import { IEvent, IEventsListApiResponse } from '../dtos';
import { environment } from 'src/environments/environment';

@Injectable({
    providedIn: 'root'
})
export class EventsService {
    apiUrl: string = environment?.apiUrl;
    constructor(private apiService: ApiService) { }

    getAllEvents(params: IPaginationParams = {}): Observable<IEventsListApiResponse> {
        return this.apiService.get<IEventsListApiResponse>(this.apiUrl + '/' + EventsEndpoints.base, params);
    }

    getEventById(id: number): Observable<IEvent> {
        return this.apiService.get<IEvent>(this.apiUrl + '/' + EventsEndpoints.getById(id));
    }
    sendFeedbackFromEvents(FeedbackData: IFeedbackData): Observable<any> {
        return this.apiService.post(`${this.apiUrl}/${EventsEndpoints.sendFeedbackFromEvent}`, FeedbackData);
    }
}
