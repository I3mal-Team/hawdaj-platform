import { HttpParams } from '@angular/common/http';
import { IPaginationParams } from './pagination.model';

export function toHttpParams(params?: IPaginationParams): HttpParams {
    let httpParams = new HttpParams();

    if (params && typeof params === 'object') {
        Object.entries(params).forEach(([key, value]) => {
            if (value !== null && value !== undefined) {
                httpParams = httpParams.append(key, value.toString());
            }
        });
    }

    return httpParams;
}
