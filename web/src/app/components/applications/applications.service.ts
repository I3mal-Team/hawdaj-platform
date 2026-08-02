import { isPlatformBrowser } from '@angular/common';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Inject, Injectable, PLATFORM_ID } from '@angular/core';
import { catchError, Observable, of } from 'rxjs';
import { roots } from 'src/app/modules/shared/configs/endPoints';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ApplicationsService {
  apiUrl: string = environment?.apiUrl;

  constructor(
    private http: HttpClient,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }

  getAll(page?: any, perPage?: any, search?: any, category_id?: any): Observable<any> {
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
    if (category_id != null && category_id !== undefined) {
      params = params.append('category_id', category_id);
    }
    return this.http.get(`${this.apiUrl}/${roots?.apps?.getAll}`, { params: params }).pipe(
      catchError((error) => {
        if (isPlatformBrowser(this.platformId)) {
          console.error('Error fetching applications:', error);
        }
        return of([]);
      })
    );
  }
  getCategories(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.apps?.getCategories}`)
      .pipe(
        catchError((error) => {
          if (isPlatformBrowser(this.platformId)) {
            console.error('Error fetching categories:', error);
          }
          return of([]);
        })
      );
  }
}