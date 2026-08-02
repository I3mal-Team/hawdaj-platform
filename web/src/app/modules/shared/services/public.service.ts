import { AbstractControl, FormControl, FormGroup, ValidatorFn, Validators } from '@angular/forms';
import { environment } from './../../../../environments/environment';
import { Inject, Injectable, PLATFORM_ID } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, Subject, Observable } from 'rxjs';
import { TranslateService } from '@ngx-translate/core';
import { isPlatformBrowser, Location } from '@angular/common';
import { keys } from '../configs/localstorage-key';
import { roots } from '../configs/endPoints';
import * as moment from 'moment';

@Injectable({
  providedIn: 'root'
})
export class PublicService {
  updateUrl = new BehaviorSubject<boolean>(false);
  closeModal = new BehaviorSubject<boolean>(false);
  recallProfileDataFuntion = new BehaviorSubject<boolean>(false);
  recallProfileDataLocalStorage = new BehaviorSubject<boolean>(false);
  pushUrlData = new BehaviorSubject<boolean>(false);
  showMap = new BehaviorSubject<boolean>(false);
  hideHeaderFooter = new BehaviorSubject<boolean>(false);
  placeCategory = new BehaviorSubject<any>(null);
  storeCategory = new BehaviorSubject<any>(null);
  restaurantCategory = new BehaviorSubject<any>(null);
  storeSubjCategory = new BehaviorSubject<any>(null);
  restaurantSubjCategory = new BehaviorSubject<any>(null);
  placeCategoryDetails = new BehaviorSubject<any>({});
  metaTagsName = new BehaviorSubject<any>({});
  language = new BehaviorSubject<any>('');
  show_loader = new Subject<boolean>();
  headers = new HttpHeaders().set('Content-Type', 'application/json');
  apiUrl: string = environment?.apiUrl;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private translate: TranslateService,
    private http: HttpClient,
    private location: Location,
  ) { }

  isFavorite(favoritable_type: string, favoritable_id: number | string, is_favorite?: boolean): Observable<any> {
    let formData: any = new FormData();
    formData.append('favoritable_type', favoritable_type);
    formData.append('favoritable_id', favoritable_id);
    return this.http.post<any>(`${this.apiUrl}/${roots.isFavorite}`, formData);
    // if (is_favorite == false) {
    //   return this.http.post<any>(`${this.apiUrl}/${roots.isFavorite}`, formData);
    // } else {
    //   return this.http.delete<any>(`${this.apiUrl}/${roots.isFavorite}/${favoritable_id}`);
    // }
  }
  isSaved(saved_type: string, saved_id: number | string, is_saved?: boolean): Observable<any> {
    let formData: any = new FormData();
    formData.append('saved_type', saved_type);
    formData.append('saved_id', saved_id);
    return this.http.post<any>(`${this.apiUrl}/${roots.isSaved}`, formData);
    // if (is_saved == false) {
    //   return this.http.post<any>(`${this.apiUrl}/${roots.isSaved}`, formData);
    // } else {
    //   return this.http.delete<any>(`${this.apiUrl}/${roots.isSaved}/${saved_id}`);
    // }
  }
  downloadExampleFn(urlRoot: any): Observable<Blob> {
    const httpOptions = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json' }),
      responseType: 'blob' as 'json'
    };
    return this.http.get<any>(`${urlRoot}`, httpOptions);
  }

  translateTextFromJson(text: string): any {
    return this.translate.instant(text);
  }
  toggleBodyScroll(enableScroll: boolean): void {
    if (isPlatformBrowser(this.platformId)) {
      if (enableScroll) {
        document?.documentElement?.classList?.remove('modal-open');
      } else {
        document?.documentElement?.classList?.add('modal-open');
      }
    }
  }
  clearValidationErrors(control: AbstractControl): void {
    control.markAsPending();
  }
  validateAllFormFields(form: any): void {
    Object.keys(form.controls).forEach(field => {
      const control = form.get(field);
      if (control instanceof FormControl) {
        control.markAsTouched({ onlySelf: true });
      } else if (control instanceof FormGroup) {
        this.validateAllFormFields(control);
      }
    });
  }
  addValidators(form: any, controls: string[], pattern?: any): any {
    controls.forEach(c => {
      form.get(c)?.setValidators(Validators.required, Validators.pattern(pattern));
      form.get(c)?.updateValueAndValidity();
    });
  }
  removeValidators(form: any, controls: string[]): any {
    controls.forEach(c => {
      form.get(c)?.clearValidators();
      form.get(c)?.updateValueAndValidity();
    });
  }
  charactersOnlyValidator(): ValidatorFn {
    return (control: AbstractControl): { [key: string]: any } | null => {
      const valid = /^[a-zA-Z]+$/.test(control.value);
      return valid ? null : { 'charactersOnly': { value: control?.value } };
    };
  }

  convertTimeOrDate(value: any, type?: any): void {
    // var date2: any = moment(value)?.format('dddd, D MMM yy');
    var date2: any = moment(value)?.format('dddd, D MMMM yy');
    var date3: any = moment(value)?.format('DD/MM/YYYY');
    var date4: any = moment(value)?.format('DD-MM-YYYY');
    var date5: any = moment(value)?.format('YYYY/MM/DD');
    var date: any = moment(value)?.format(' D MMMM yy');
    var time: any = moment(value)?.format('hh:mm A');
    var birthDate: any = moment(value).format('D MMM yy');
    var appointmentDate: any = new Date(value);

    if (type == 'date') {
      return date;
    }
    if (type == 'date2') {
      return date2;
    }
    if (type == 'date3') {
      return date3;
    }
    if (type == 'date4') {
      return date4;
    }
    if (type == 'date5') {
      return date5;
    }
    if (type == 'birthDate') {
      return birthDate;
    }
    if (type == 'time') {
      return time;
    }
    var optionsTime: any = {
      hour: '2-digit',
      minute: '2-digit',
      hour12: true
    };
    var optionsDate: any = {
      weekday: 'short',
      month: 'short',
      day: 'numeric'
    };

    if (type == 'appointmentDate') {
      return appointmentDate?.toLocaleString('en-US', optionsDate);
    }
    if (type = 'appointmentTime') {
      return appointmentDate?.toLocaleTimeString('en-US', optionsTime);
    }
  }

  getDateArrayFromDateRange(dateRange: string): Date[] {
    // dateRange = "2023/10/28-2023/12/01"
    // Split the date range string to get start and end dates
    const [start, end] = dateRange.split('-').map(dateStr => new Date(dateStr));
    const dateArray: Date[] = [];

    // Initialize a new date object with the start date
    let currentDate = new Date(start);

    // While the current date is less than or equal to the end date
    while (currentDate <= end) {
      // Push the current date to the array
      dateArray.push(new Date(currentDate));  // Push a new instance of the date to ensure original is not modified
      // Move to the next date
      currentDate.setDate(currentDate.getDate() + 1);
    }
    return dateArray;
  }
  addDays(dateStr: string, days: number): string {
    const date = new Date(dateStr);
    date.setDate(date.getDate() + days);
    // Format date as YYYY-MM-DD
    const formattedDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    return formattedDate;
  }

  removeLastNthElements(array: any[], nth: number): any {
    if (array?.length >= nth) {
      array.splice(-nth);
    } else {
      array.splice(0, array?.length);
    }
    return array;
  }

  slicedData(data: any[], number: any): any[] {
    return data?.slice(0, number);
  }
  createGoogleMapsLink(latitude: number, longitude: number): string {
    const baseUrl = "https://www.google.com/maps/search/?api=1&query=";
    return `${baseUrl}${latitude},${longitude}`;
  }
  calculateDistance(lat1: number, lon1: number, lat2: number, lon2: number): number {
    const R = 6371;
    const dLat = this.deg2rad(lat2 - lat1);
    const dLon = this.deg2rad(lon2 - lon1);
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) *
      Math.sin(dLon / 2) * Math.sin(dLon / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = R * c;
    return distance;
  }
  deg2rad(deg: number): number {
    return deg * (Math.PI / 180);
  }
  getMonths(): any {
    let monthNames = [
      this.translateTextFromJson("monthNamesShort.Jan"), this.translateTextFromJson("monthNamesShort.Feb"), this.translateTextFromJson("monthNamesShort.Mar"), this.translateTextFromJson("monthNamesShort.Apr"), this.translateTextFromJson("monthNamesShort.May"), this.translateTextFromJson("monthNamesShort.Jun"),
      this.translateTextFromJson("monthNamesShort.Jul"), this.translateTextFromJson("monthNamesShort.Aug"), this.translateTextFromJson("monthNamesShort.Sep"), this.translateTextFromJson("monthNamesShort.Oct"), this.translateTextFromJson("monthNamesShort.Nov"), this.translateTextFromJson("monthNamesShort.Dec")
    ];
    return monthNames;
  }

  getGenderList(): any {
    let list: any = [
      { label: this.translateTextFromJson('general.male'), value: 'male' },
      { label: this.translateTextFromJson('general.female'), value: 'female' }
    ];
    return list;
  }

  uploadFile(data: any): Observable<any> {
    return this.http.post<any>(this.apiUrl + '/' + roots.auth.uploadFile, data);
  }
  getSocialOptions(): any {
    let arr: any = [
      {
        id: 'facebook',
        title: 'facebook',
      },
      {
        id: 'linkedin',
        title: 'linkedin',
      },
      {
        id: 'youtube',
        title: 'youtube',
      },
      {
        id: 'twitter',
        title: 'twitter',
      },
      {
        id: 'instagram',
        title: 'instagram',
      }
    ];
    return arr;
  }

  getCurrentLanguageOldCode(): string | null {
    const validSegments: string[] = ['ar', 'en', 'ru', 'zh'];
    let currentLangKey: string | null = null;

    if (isPlatformBrowser(this.platformId)) {
      // This block will run only on the client side
      const url: any = window.location.href;
      const parsedUrl: any = new URL(url);
      const firstSegment: any = parsedUrl.pathname.split('/')[1];

      // Check if the first segment is a valid language key
      if (validSegments.includes(firstSegment)) {
        currentLangKey = firstSegment;
      } else {
        // Fallback to stored language in localStorage
        currentLangKey = localStorage.getItem(keys.language) || this.translate.getDefaultLang();
      }
    } else {
      // This block will run only on the server side
      // Provide a default language or handle the logic for SSR
      currentLangKey = 'ar'; // Set default language for SSR, adjust as needed
    }

    return currentLangKey;
  }
  getCurrentLanguage(): string | null {
    const validSegments: string[] = ['ar', 'en', 'ru', 'zh'];
    let currentLangKey: string | null = null;

    if (isPlatformBrowser(this.platformId)) {
      // This block will run only on the client side
      const path = this.location.path().split('/')[1];

      // Check if the first segment is a valid language key
      if (validSegments.includes(path)) {
        localStorage.setItem(keys.language, path);
        return path
      } else {
        const storedLang = localStorage.getItem(keys.language);
        const lang = storedLang ? storedLang : 'ar';
        // const lang = storedLang ? storedLang : this.translate.getDefaultLang();
        this.updatePath(`${lang}${this.location.path()}`);
        localStorage.setItem(keys.language, lang);
        return lang;
      }
    } else {
      // Server-side execution
      const path = this.location.path().split('/')[1]; // Using Location to get path
      // Check if the first segment is a valid language key
      if (validSegments.includes(path)) {
        // localStorage.setItem(keys.language, path);
        return path;
      } else {
        // Provide a default language or handle the logic for SSR
        localStorage.setItem(keys.language, 'ar');
        return 'ar'; // Adjust as needed
      }
    }
    // return decodeURIComponent(path);
  }

  updatePath(newPath: string): void {
    if (this.location.path() != newPath)
      this.location.replaceState(newPath);
  }
}
