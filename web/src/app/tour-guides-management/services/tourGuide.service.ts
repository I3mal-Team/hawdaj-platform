import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from '../../Common/core/data-access/http-client/clients/api.service';
import { TourGuidesEndpoints } from '../configs/tourGuides-endpoints';
import { IFeedbackData, IPaginationParams } from 'src/app/Common/core';
import { ITourGuide, ITourGuideListApiResponse } from '../dtos';
import { environment } from 'src/environments/environment';

@Injectable({
    providedIn: 'root'
})
export class tourGuideService {
    apiUrl: string = environment?.apiUrl;
    constructor(private apiService: ApiService) { }
    getAll(params: IPaginationParams = {}): Observable<ITourGuideListApiResponse> {
        return this.apiService.get<ITourGuideListApiResponse>(this.apiUrl + '/' + TourGuidesEndpoints.getAll, params);
    }

    getTourGuideById(id: number): Observable<ITourGuide> {
        return this.apiService.get<ITourGuide>(this.apiUrl + '/' + TourGuidesEndpoints.getById(id));
    }
    sendFeedbackFromTourGuide(FeedbackData: IFeedbackData): Observable<any> {
        return this.apiService.post(`${this.apiUrl}/${TourGuidesEndpoints.sendFeedbackFromStore}`, FeedbackData);
    }
}
