import { Observable } from "rxjs";
import { ApiService } from "../../core";
import { Injectable } from "@angular/core";
import { SharedEndpoints } from "../configs";


@Injectable({
    providedIn: 'root'
})
export class LanguagesService {
    constructor(private apiService: ApiService) { }

    getLanguages(): Observable<any> {
        return this.apiService.get(SharedEndpoints.languages);
    }

}
