import { roots } from 'src/app/modules/shared/configs/endPoints';
import { HttpClient, HttpParams } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class TourGuidesService {
  apiUrl: string = environment?.apiUrl;

  constructor(
    private http: HttpClient
  ) { }

  getAll(page?: any, perPage?: any, search?: any, region_id?: any, language_id?: any, experience?: any): Observable<any> {
    let params = new HttpParams();
    if (page != null) {
      params = params.append('page', page);
    }
    if (perPage) {
      params = params.append('per_page', perPage);
    }
    if (search) {
      params = params.append('search', search);
    }
    if (region_id != null && region_id !== undefined) {
      params = params.append('region_id', region_id);
    }
    if (language_id != null && language_id !== undefined) {
      params = params.append('language_id', language_id);
    }
    if (experience != null && experience !== undefined) {
      params = params.append('experience', experience);
    }
    return this.http.get(`${this.apiUrl}/${roots?.tourGuides?.getAll}`, { params: params });
  }

  getTourGuideProfile(): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}${roots.tourGuides.getTourGuideProfile}`);
  }
  getTourGuideById(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.tourGuides?.getAll}/` + id);
  }
  sendFeedbackFromTourGuide(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/${roots?.tourGuides?.sendFeedbackFromTourGuide}`, data);
  }
  updateTourGuideProfile(data: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}${roots.tourGuides.updateTourGuideProfile}`, data);
  }

  uploadPhotoFile(data: any): Observable<any> {
    return this.http.post<any>(this.apiUrl + '/' + roots.tourGuides.updatePhoto, data);
  }
}
