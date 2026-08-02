import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from '../../Common/core/data-access/http-client/clients/api.service';
import { StoriesEndpoints } from '../configs/stories-endpoints';
import { IPaginationParams } from 'src/app/Common/core';
import { IStory, IStoriesListApiResponse } from '../dtos';
import { environment } from 'src/environments/environment';

@Injectable({
    providedIn: 'root'
})
export class StoriesService {
    apiUrl: string = environment?.apiUrl;
    constructor(private apiService: ApiService) { }

    getPlaces(params: IPaginationParams = {}): Observable<IStoriesListApiResponse> {
        return this.apiService.get<IStoriesListApiResponse>(this.apiUrl + '/' + StoriesEndpoints.getAll, params);
    }

    getStoryById(id: number): Observable<IStory> {
        return this.apiService.get<IStory>(this.apiUrl + '/' + StoriesEndpoints.getById(id));
    }
    getRecentStories(params: IPaginationParams = {}): Observable<any> {
        return this.apiService.get<IStoriesListApiResponse>(this.apiUrl + '/' + StoriesEndpoints.base, params);
    }
    getCategories(): Observable<any> {
        // Construct the full URL using apiUrl from environment and the categories endpoint
        return this.apiService.get<IStoriesListApiResponse>(this.apiUrl + '/' + StoriesEndpoints.categories);
    }
}
