import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from '../../Common/core/data-access/http-client/clients/api.service';
import { RestaurantsEndpoints } from '../configs/restaurants-endpoints';
import { IFeedbackData, IPaginationParams } from 'src/app/Common/core';
import { IRestaurant, IRestaurantsListApiResponse } from '../dtos';
import { environment } from 'src/environments/environment';
import { ICategoryItem } from '../models';
import { ICity, IRegion } from 'src/app/Common/shared';

@Injectable({
    providedIn: 'root'
})
export class RestaurantsService {
    apiUrl: string = environment?.apiUrl;
    constructor(private apiService: ApiService) { }

    getRestaurants(params: IPaginationParams = {}): Observable<IRestaurantsListApiResponse> {
        return this.apiService.get<IRestaurantsListApiResponse>(this.apiUrl + '/' + RestaurantsEndpoints.base, params);
    }
    getRestaurantById(slug: string): Observable<IRestaurant> {
        return this.apiService.get<IRestaurant>(this.apiUrl + '/' + RestaurantsEndpoints.getBySlug(slug));
    }
    getRestaurantMenu(id: number, params: IPaginationParams = {}): Observable<any> {
        return this.apiService.get(`${this.apiUrl}/${RestaurantsEndpoints.base}/${id}/menus`, params);
    }
    getOffers(id?: any): Observable<any> {
        return this.apiService.get(`${this.apiUrl}/${RestaurantsEndpoints.base}/${id}/offers`);
    }
    favoriteRestaurant(id?: any): Observable<any> {
        return this.apiService.post(`${this.apiUrl}/${RestaurantsEndpoints.base}/favorite`, { id });
    }
    getFoodCategories(): Observable<any> {
        return this.apiService.get<ICategoryItem>(`${this.apiUrl}/${RestaurantsEndpoints.base}/food-categories`);
    }
    sendFeedbackFromRestaurant(FeedbackData: any): Observable<any> {
        return this.apiService.post<IFeedbackData>(`${this.apiUrl}/rates`, FeedbackData);
    }
    getCategories(params: IPaginationParams = {}): Observable<any> {
        return this.apiService.get<ICategoryItem>(`${this.apiUrl}/${RestaurantsEndpoints.base}/categories`, params);
    }
    getRegions(): Observable<any> {
        return this.apiService.get<IRegion>(`${this.apiUrl}/${RestaurantsEndpoints.regions}`);
    }
    getCities(regionId: number): Observable<any> {
        return this.apiService.get<ICity>(`${this.apiUrl}/${RestaurantsEndpoints.regions}/${regionId}/cities`);
    }

}
