import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from '../../Common/core/data-access/http-client/clients/api.service';
import { PlacesEndpoints } from '../configs/places-endpoints';
import { IPaginationParams } from 'src/app/Common/core';
import { IPlace, IPlacesListApiResponse } from '../dtos';

@Injectable({
    providedIn: 'root'
})
export class PlacesService {
    constructor(private apiService: ApiService) { }

    getPlaces(params: IPaginationParams = {}): Observable<IPlacesListApiResponse> {
        return this.apiService.get<IPlacesListApiResponse>(PlacesEndpoints.getAll, params);
    }

    getPlaceById(id: number): Observable<IPlace> {
        return this.apiService.get<IPlace>(PlacesEndpoints.getById(id));
    }

    getRelatedPlaces(id: number): Observable<IPlacesListApiResponse> {
        return this.apiService.get<IPlacesListApiResponse>(PlacesEndpoints.relatedPlaces(id));
    }

    getCategories(params?: any): Observable<any> {
        return this.apiService.get(PlacesEndpoints.categories, params);
    }

    getSubCategories(categoryId: number): Observable<any> {
        return this.apiService.get(PlacesEndpoints.subCategories(categoryId));
    }

    getPrices(): Observable<any> {
        return this.apiService.get(PlacesEndpoints.prices);
    }

    getHeroSliderPlaces(): Observable<any> {
        return this.apiService.get(PlacesEndpoints.heroSlider);
    }

    getPlacesNames(): Observable<any> {
        return this.apiService.get(PlacesEndpoints.placeNames);
    }

    getTripSteps(): Observable<any> {
        return this.apiService.get(PlacesEndpoints.tripSteps);
    }

    savePlace(id: number, type?: string): Observable<any> {
        return this.apiService.post(PlacesEndpoints.savePlace, { id, type });
    }
}
