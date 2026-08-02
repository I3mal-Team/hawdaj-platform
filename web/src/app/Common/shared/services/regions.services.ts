import { Observable } from "rxjs";
import { ApiService } from "../../core";
import { Injectable } from "@angular/core";
import { SharedEndpoints } from "../configs";


@Injectable({
    providedIn: 'root'
})
export class RegionsService {
    constructor(private apiService: ApiService) { }

    getRegions(): Observable<any> {
        return this.apiService.get(SharedEndpoints.regions);
    }

}
