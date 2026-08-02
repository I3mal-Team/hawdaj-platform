import { ITripDetailsResponseDto, ITripsResponseDto, IPrepareTripResponseDto, IPrepareTripRequestDto, ISaveTripRequestDto, ISaveTripResponseDto, IReprepareTripResponseDto, IReprepareTripRequestDto } from '../dtos';
import { HttpClient, HttpParams } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { inject, Injectable } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class TripsService {
  private readonly http = inject(HttpClient);
  private readonly baseUrlAll = `${environment.apiUrl}/v2/trips/my-trips`;
  private readonly baseUrlDelete = `${environment.apiUrl}/v2/trips`;
  private readonly baseUrlSavedTripByToken = `${environment.apiUrl}/v2/trips/view`;
  private readonly baseUrlPrepareTrip = `${environment.apiUrl}/v2/trips/prepare`;
  private readonly baseUrlSaveTrip = `${environment.apiUrl}/v2/trips/save`;
  private readonly baseUrlReprepareTrip = `${environment.apiUrl}/v2/trips/reprepare`;

  getAllTrips(page = 1, perPage = 10, search?: string, vehicleId?: number | null) {
    let params = new HttpParams()
      .set('page', String(page))
      .set('per_page', String(perPage));

    if (search) params = params.set('search', search);
    if (vehicleId) params = params.set('vehicle_id', String(vehicleId));

    return this.http.get<ITripsResponseDto>(this.baseUrlAll, { params });
  }

  deleteTrip(token?: string) {
    if (!token) throw new Error('Trip token is required');
    return this.http.delete(`${this.baseUrlDelete}/${token}`);
  }

  /** Get trip by token */
  getTripByToken(token: string) {
    return this.http.get<ITripDetailsResponseDto>(`${this.baseUrlSavedTripByToken}/${token}`);
  }

  /** Get prepared trip by token */
  getPreparedTripByToken(token: string) {
    return this.http.get<IPrepareTripResponseDto>(`${this.baseUrlPrepareTrip}/${token}`);
  }

  /** Prepare Trip - Generate optimized trip plan */
  prepareTrip(request: IPrepareTripRequestDto) {
    const formData = new FormData();

    formData.append('start_date', request.start_date);
    formData.append('end_date', request.end_date);
    formData.append('start_region_id', String(request.start_region_id));
    formData.append('end_region_id', String(request.end_region_id));
    formData.append('places_per_day', String(request.places_per_day));
    formData.append('vehicleType', request.vehicleType);

    // Append array fields
    request.categories.forEach(category => {
      formData.append('categories[]', String(category));
    });

    request.price_range.forEach(price => {
      formData.append('price_range[]', String(price));
    });

    return this.http.post<IPrepareTripResponseDto>(this.baseUrlPrepareTrip, formData);
  }

  /** Re-prepare trip using prepare token */
  reprepareTrip(request: IReprepareTripRequestDto) {
    const formData = new FormData();
    formData.append('prepare_token', request.prepare_token);
    return this.http.post<IReprepareTripResponseDto>(this.baseUrlReprepareTrip, formData);
  }

  /** Save Trip - Save a prepared trip with name */
  saveTrip(request: ISaveTripRequestDto | FormData) {
    let formData: FormData;

    if (request instanceof FormData) {
      // If FormData is passed directly, use it
      formData = request;
    } else {
      // Otherwise, create FormData from request object
      formData = new FormData();
      formData.append('name', request.name);
      formData.append('prepare_token', request.prepare_token);
    }

    return this.http.post<ISaveTripResponseDto>(this.baseUrlSaveTrip, formData);
  }
}
