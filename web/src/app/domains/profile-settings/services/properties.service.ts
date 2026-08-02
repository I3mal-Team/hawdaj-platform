import { HttpClient } from '@angular/common/http';
import { inject, Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { ProfileSettingsManagementCollections } from '../collections/profile-settings.collections';
import { ICreatePropertyRequestDto } from '../dtos/requests/create-property-request.dto';
import { ICreatePropertyResponseDto } from '../dtos/responses/create-property-response.dto';

@Injectable({ providedIn: 'root' })
export class PropertiesService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = environment.apiUrl;

  createProperty(request: ICreatePropertyRequestDto): any {
    const formData = new FormData();

    // Basic fields
    formData.append('type', request.type);
    formData.append('title', request.title);
    formData.append('description', request.description);
    formData.append('lat', request.lat);
    formData.append('long', request.long);
    formData.append('address', request.address);
    formData.append('region_id', request.region_id);
    formData.append('city_id', request.city_id);

    // Store type field (con_type)
    if (request.con_type) {
      formData.append('con_type', request.con_type);
    }

    // Store address type field
    if (request.address_type) {
      formData.append('address_type', request.address_type);
    }

    // Categories
    if (request.categories && request.categories.length > 0) {
      request.categories.forEach(category => {
        formData.append('categories[]', category);
      });
    }

    // Social media links
    if (request.whatsapp) {
      formData.append('whatsapp', request.whatsapp);
    }
    if (request.facebook_link) {
      formData.append('facebook_link', request.facebook_link);
    }
    if (request.instagram_link) {
      formData.append('instagram_link', request.instagram_link);
    }
    if (request.website_link) {
      formData.append('website_link', request.website_link);
    }

    // Files
    if (request.ownership_proof_file) {
      formData.append('ownership_proof_file', request.ownership_proof_file);
    }
    if (request.image) {
      formData.append('image', request.image);
    }
    if (request.menu_file) {
      formData.append('menu_file', request.menu_file);
    }

    // Food categories (for zad type)
    if (request.food_categories && request.food_categories.length > 0) {
      request.food_categories.forEach(category => {
        formData.append('food_categories[]', category);
      });
    }

    // Best seasons (for place type)
    if (request.best_seasons && request.best_seasons.length > 0) {
      request.best_seasons.forEach(season => {
        formData.append('best_seasons[]', season);
      });
    }

    // Prices (for place type)
    if (request.prices && request.prices.length > 0) {
      request.prices.forEach(price => {
        formData.append('prices[]', price);
      });
    }

    // Event fields
    if (request.video_link) {
      formData.append('video_link', request.video_link);
    }
    if (request.ticket_link) {
      formData.append('ticket_link', request.ticket_link);
    }
    if (request.date_from) {
      formData.append('date_from', request.date_from);
    }
    if (request.date_to) {
      formData.append('date_to', request.date_to);
    }

    console.log('=== FORM DATA KEYS ===');
    for (let pair of (formData as any).entries()) {
      console.log(pair[0] + ': ' + pair[1]);
    }

    return this.http.post<ICreatePropertyResponseDto>(
      `${this.baseUrl}/${ProfileSettingsManagementCollections.Properties}`,
      formData
    );
  }
}









