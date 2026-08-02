import { environment } from '../../environments/environment';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { roots } from '../modules/shared/configs/endPoints';

@Injectable({
  providedIn: 'root'
})
export class TripsService {
  apiUrl: string = environment?.apiUrl;

  constructor(
    private http: HttpClient
  ) { }

  getTripForSave(id: any): Observable<any> {
    let params = new HttpParams();
    if (id != null) {
      params = params.append('id', id);
    }
    return this.http.get(
      `${this.apiUrl}/${roots?.trips?.getTripForSave}`,
      { params: params });
  }
  regenerateTrip(id: any): Observable<any> {
    let params = new HttpParams();
    if (id != null) {
      params = params.append('id', id);
    }
    return this.http.get(
      `${this.apiUrl}/${roots?.trips?.regenerateTrip}`,
      { params: params });
  }

  prepareTrip(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/${roots?.trips?.prepareTrip}`, data);
  }
  getpreparedTrip(token: string): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.trips?.prepareTrip}/${token}`,);
  }

  saveTrip(data: any): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/${roots?.trips?.saveTrip}`,
      data);
  }
  sendTripEmail(data: any): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/${roots?.trips?.sendTripEmail}`,
      data);
  }

  getSuggestedPlaces(skipCount?: any, maxResultCount?: any, keyWord?: any): Observable<any> {
    let params = new HttpParams();
    if (skipCount != null) {
      params = params.append('skipCount', skipCount);
    }
    if (maxResultCount != null) {
      params = params.append('maxResultCount', maxResultCount);
    }
    if (keyWord != null) {
      params = params.append('keyWord', keyWord);
    }
    return this.http.get(
      `${this.apiUrl}/${roots?.trips?.getSuggestedPlaces}`, { params: params });
  }
  addPlaces(data: any): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/${roots?.trips?.addPlaces}`, data);
  }

  getAllTrips(page?: any, perPage?: any, search?: any): Observable<any> {
    let params = new HttpParams();
    if (page != null) {
      params = params.append('page', page);
    }
    if (perPage != null) {
      params = params.append('per_page', perPage);
    }
    if (search != null) {
      params = params.append('search', search);
    }
    return this.http.get(`${this.apiUrl}/${roots?.trips?.getAllTrips}`, { params: params });
  }
  getTripById(id: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.trips?.viewTrip}/${id}`);
  }
  deleteTrip(id?: any): Observable<any> {
    let params = new HttpParams();
    if (id != null) {
      params = params.append('id', id);
    }
    return this.http.delete(
      `${this.apiUrl}/${roots?.trips?.deleteTrip}/${id}`);
  }
}
