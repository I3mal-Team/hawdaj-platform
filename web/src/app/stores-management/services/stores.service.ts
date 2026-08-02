import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from '../../Common/core/data-access/http-client/clients/api.service';
import { StoresEndpoints } from '../configs/stores-endpoints';
import { IFeedbackData, IPaginationParams } from 'src/app/Common/core';
import { IStoresListApiResponse } from '../dtos';
import { environment } from 'src/environments/environment';
import { ICategoryItem } from '../models';
import { ICity, IRegion } from 'src/app/Common/shared';

@Injectable({
    providedIn: 'root'
})
export class StoresService {
    apiUrl: string = environment?.apiUrl;
    constructor(private apiService: ApiService) { }

    getStores(params: IPaginationParams = {}): Observable<IStoresListApiResponse> {
        return this.apiService.get<IStoresListApiResponse>(this.apiUrl + '/' + StoresEndpoints.getAllStores, params);
    }
    getCategories(): Observable<ICategoryItem> {
        return this.apiService.get<ICategoryItem>(this.apiUrl + '/' + StoresEndpoints.getStoresCategories);
    }
    getTopStores(params: IPaginationParams = {}): Observable<IStoresListApiResponse> {
        return this.apiService.get(this.apiUrl + '/' + StoresEndpoints.getAllStores, params);
    }
    getRegions(): Observable<IRegion> {
        return this.apiService.get(this.apiUrl + '/' + 'regions');
    }
    getCities(regionId: number): Observable<ICity> {
        return this.apiService.get(this.apiUrl + '/' + 'regions/' + regionId + '/cities');
    }
    getStoreById(id: number): Observable<any> {
        return this.apiService.get(`${this.apiUrl}/${StoresEndpoints.getStoreById}/${id}`);
    }
    sendFeedbackFromStore(FeedbackData: IFeedbackData): Observable<any> {
        return this.apiService.post(`${this.apiUrl}/${StoresEndpoints.sendFeedbackFromStore}`, FeedbackData);
    }
    // getStoresStats(id: number): Observable<any> {
    //     return this.apiService.get<any>(StoresEndpoints.getById(id));
    // }

    // getRelatedPlaces(id: number): Observable<IStoresListApiResponse> {
    //     return this.apiService.get<IStoresListApiResponse>(StoresEndpoints.relatedStores(id));
    // }



    // getSubCategories(categoryId: number): Observable<any> {
    //     return this.apiService.get(StoresEndpoints.subCategories(categoryId));
    // }

    // getPrices(): Observable<any> {
    //     return this.apiService.get(StoresEndpoints.prices);
    // }

    // getHeroSliderPlaces(): Observable<any> {
    //     return this.apiService.get(StoresEndpoints.heroSlider);
    // }

    // getPlacesNames(): Observable<any> {
    //     return this.apiService.get(StoresEndpoints.placeNames);
    // }

    // getTripSteps(): Observable<any> {
    //     return this.apiService.get(StoresEndpoints.tripSteps);
    // }

    // savePlace(id: number, type?: string): Observable<any> {
    //     return this.apiService.post(StoresEndpoints.savePlace, { id, type });
    // }
}
