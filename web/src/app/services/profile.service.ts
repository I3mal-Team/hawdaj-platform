import { environment } from '../../environments/environment';
import { HttpClient, HttpParams } from '@angular/common/http';
import { roots } from '../modules/shared/configs/endPoints';
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ProfileService {
  apiUrl: string = environment?.apiUrl;
  recallGetCurrent14Stories = new BehaviorSubject<boolean>(false);

  constructor(
    private http: HttpClient
  ) { }

  addLandmark(data: any, type?: any): Observable<any> {
    let params = new HttpParams();
    if (type != null) {
      params = params.append('type', type);
    }
    return this.http.post(
      `${this.apiUrl}/${roots?.profile?.addLandmark}`, data,
      { params: params });
  }
  uploadStory(data: any, type?: any): Observable<any> {
    let params = new HttpParams();
    if (type != null) {
      params = params.append('type', type);
    }
    return this.http.post(
      `${this.apiUrl}/${roots?.profile?.uploadStory}`, data,
      { params: params });
  }

  uploadProfileFile(data: any): Observable<any> {
    return this.http.post<any>(this.apiUrl + '/' + roots.profile.updatePhoto, data);
  }

  addNewStory(data: any): Observable<any> {
    return this.http.post<any>(this.apiUrl + '/' + roots.profile.addNewStory, data);
  }

  getMyLastDayStories(): Observable<any> {
    return this.http.get<any>(this.apiUrl + '/' + roots.profile.myLastDayStories);
  }

  addCommentToStory(data: any): Observable<any> {
    return this.http.post<any>(this.apiUrl + '/' + roots.profile.addCommentToStory, data);
  }
}
