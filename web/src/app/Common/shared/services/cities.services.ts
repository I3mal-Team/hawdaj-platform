import { Observable } from "rxjs";
import { ApiService } from "../../core";
import { Injectable } from "@angular/core";
import { SharedEndpoints } from "../configs";


@Injectable({
    providedIn: 'root'
})
export class CitiesService {
    constructor(private apiService: ApiService) { }

    getCities(regionId: number): Observable<any> {
        return this.apiService.get(SharedEndpoints.cities(regionId));
    }

}
