import { Observable } from "rxjs";
import { ApiService } from "../../core";
import { Injectable } from "@angular/core";
import { SharedEndpoints } from "../configs";


@Injectable({
    providedIn: 'root'
})
export class FeedbackService {
    constructor(private apiService: ApiService) { }

    sendFeedback(data: any): Observable<any> {
        return this.apiService.post(SharedEndpoints.feedback, data);
    }
}
