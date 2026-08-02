import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { IPaginationParams, toHttpParams } from 'src/app/Common/core';

@Injectable({
    providedIn: 'root'
})
export class ApiService {
    constructor(private http: HttpClient) { }

    get<T>(url: string, params?: IPaginationParams): Observable<T> {
        const httpParams = toHttpParams(params);
        return this.http.get<T>(url, { params: httpParams });
    }


    post<T>(url: string, body: any): Observable<T> {
        return this.http.post<T>(url, body);
    }
}
