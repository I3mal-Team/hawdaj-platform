import { environment } from '../../environments/environment';
import { HttpClient, HttpParams } from '@angular/common/http';
import { roots } from '../modules/shared/configs/endPoints';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class EventsService {
  apiUrl: string = environment?.apiUrl;

  constructor(
    private http: HttpClient
  ) { }

  getAllEvents(page?: any, perPage?: any, keyWord?: any, dateRange?: any, addressType?: any, top_events?: any): Observable<any> {
    let params = new HttpParams();
    if (page != null) {
      params = params.append('page', page);
    }
    if (perPage != null) {
      params = params.append('per_page', perPage);
    }
    if (keyWord != null && keyWord != '') {
      params = params.append('search', keyWord);
    }
    if (dateRange != null) {
      params = params.append('daterange', dateRange);
    }
    if (addressType != null) {
      params = params.append('address_type', addressType);
    }
    if (top_events != null) {
      params = params.append('top_events', top_events);
    }
    return this.http.get(`${this.apiUrl}/${roots?.events?.getAllEvents}`, { params: params });
  }
  getEventById(id: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.events?.getAllEvents}/${id}`);
  }
  rateEvent(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/${roots?.events?.rate}`, data);
  }
  sendFeedbackFromEvent(FeedbackData: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/rates`, FeedbackData);
  }

}
