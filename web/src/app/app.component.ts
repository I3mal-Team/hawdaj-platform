import { DOCUMENT, isPlatformBrowser, isPlatformServer, registerLocaleData } from '@angular/common';
import { Component, Inject, PLATFORM_ID, Renderer2, OnInit, AfterViewInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, NavigationEnd, NavigationStart, Router } from '@angular/router';
import { keys } from './modules/shared/configs/localstorage-key';
import { TranslateService } from '@ngx-translate/core';
import { PrimeNGConfig } from 'primeng/api';
import { filter, interval, map, Observable, Subscription } from 'rxjs';
import * as Aos from 'aos';

import localeAr from '@angular/common/locales/ar';
import localeRu from '@angular/common/locales/ru';
import localeZh from '@angular/common/locales/zh';

import { tap, catchError, finalize, switchMap } from 'rxjs/operators';
import { PublicService } from './modules/shared/services/public.service';
import { PlacesService } from './services/places.service';
import { RestaurantsService } from './services/restaurants.service';
import { StoresService } from './services/stores.service';
import { TranslationService } from './services/translation.service';
import { AlertsService } from './services/alerts.service';
import { LocalizationLanguageService } from './modules/shared/services/localization-language.service';
import { MetadataService } from './modules/shared/services/metadata.service';
import { environment } from '../environments/environment';
import { LocationLatLongFacade } from './core';

registerLocaleData(localeAr);
registerLocaleData(localeRu);
registerLocaleData(localeZh);

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit, AfterViewInit, OnDestroy {
  private subscriptions: Subscription[] = [];
  title = 'Hawdaj';

  private locationIntervalSub?: Subscription;

  languages = ['en', 'ar', 'ru', 'zh']; // List of supported languages
  browserLang: string | null = null;
  currentTheme: string | null = null;
  favIcon: HTMLLinkElement | null = null;
  platform: 'Browser' | 'Server' | 'Unknown' = 'Unknown';
  currentLanguage: string | null = null;
  private navigationHistory: string[] = [];
  showSpinner = true;

  constructor(
    private _LocalizationLanguageService: LocalizationLanguageService,
    private _LocationLatLongFacade: LocationLatLongFacade,
    @Inject(PLATFORM_ID) private platformId: Object,
    private translationService: TranslationService,
    private restaurantsService: RestaurantsService,
    @Inject(DOCUMENT) private document: Document,
    private translateService: TranslateService,
    private activatedRoute: ActivatedRoute,
    private publicService: PublicService,
    private primengConfig: PrimeNGConfig,
    private alertsService: AlertsService,
    private placesService: PlacesService,
    private storesService: StoresService,
    private renderer: Renderer2,
    private router: Router,
    private metadataService: MetadataService,
  ) {
    this.initializeApp();
    this.publicService.updateUrl.subscribe((res: any) => {
      if (res) {
        this.checkAndSetLanguageInUrl();
      }
    });
  }

  private startLocationTracking(): void {
    if (!isPlatformBrowser(this.platformId)) return;

    this.locationIntervalSub = interval(3 * 60 * 1000) // 3 minutes
      .pipe(
        switchMap(() => this._LocationLatLongFacade.updateLocation())
      )
      .subscribe();
  }

  ngOnInit(): void {

    if (isPlatformBrowser(this.platformId)) {
      this._LocationLatLongFacade.updateLocation().subscribe();
      Aos?.init();
      this.startLocationTracking();
    }
    this.primengConfig.ripple = true;
  }

  ngAfterViewInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.setFavicon();
      this.updateMetaTags();
    }
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach(subscription => subscription.unsubscribe());

    this.locationIntervalSub?.unsubscribe();
  }

  private initializeApp(): void {
    this.showSpinner = true;
    setTimeout(() => this.showSpinner = false, 250);

    if (isPlatformBrowser(this.platformId)) {
      this.checkAndSetLanguageInUrl(); // Ensure language is part of the URL on app load
      this.initBrowserFeatures();
    } else if (isPlatformServer(this.platformId)) {
      this.platform = 'Server';
    }

    this.subscribeToRouterEvents();
    this.setupLanguageSupport();
    if (isPlatformBrowser(this.platformId)) {
      setTimeout(() => {
        this.loadGlobalCategories();
      }, 501); //Time for TranslationService to update DOM
    }
  }

  private initBrowserFeatures(): void {
    this.platform = 'Browser';
    this.navigationHistory = JSON.parse(localStorage.getItem(keys.navigationHistory) || '[]') || [];
  }

  private checkAndSetLanguageInUrl(): void {
    if (!isPlatformBrowser(this.platformId)) return;
    const currentUrl = this.router.url;
    let urlSegments = currentUrl.split('/');
    console.log(this.publicService.getCurrentLanguage());

    if (this.languages.includes(urlSegments[1])) {
      console.log(`First segment contains a supported language: ${urlSegments[1]}`);
    }

    // Remove any existing language code (if present) from stored URL
    let storedUrl: any = '';
    if (storedUrl === '') {
      storedUrl = currentUrl;
    } else {
      if (storedUrl) {
        let storedUrlSegments = storedUrl.split('/');
        // If the stored URL contains a language code, remove it
        if (this.languages.includes(storedUrlSegments[1])) {
          storedUrlSegments.splice(1, 1); // Remove the language from the URL
        }
        // Update the `storedUrl` in localStorage without the language code
        sessionStorage.setItem('storedUrl', storedUrlSegments.join('/'));
        // Ensure `urlSegments` reflects the correct state if storedUrl was loaded initially
        urlSegments = storedUrlSegments;
      }
      // Check if the first segment is a valid language
      const languageFromUrl = urlSegments[1]; // Check after base URL
      if (!this.languages.includes(languageFromUrl)) {
        // If the language code is missing, add the default or browser language
        this.currentLanguage = this.getLanguageFromLocalStorage() || this.translateService.getBrowserLang() || 'en';
        // Insert the correct language at the beginning of the URL and ensure it is followed by a `/`
        urlSegments.splice(1, 0, this.currentLanguage);
        // Update the `storedUrl` in localStorage
        const newUrl = urlSegments.join('/');
        sessionStorage.setItem('storedUrl', newUrl);
        // Navigate to the new URL but without reloading the page
        this.router.navigateByUrl(newUrl);
      } else {
        // If a valid language is found in the URL, use it
        this.currentLanguage = languageFromUrl;
        this.applyLanguage(this.currentLanguage);
        // Ensure the current URL is stored without duplicate languages
        sessionStorage.setItem('storedUrl', currentUrl);
      }
    }
  }
  private subscribeToRouterEvents(): void {
    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd),
      map(() => {
        let child = this.activatedRoute?.firstChild;
        while (child) {
          if (child.firstChild) {
            child = child.firstChild;
          } else if (child.snapshot?.data) {
            return child.snapshot.data;
          } else {
            return null;
          }
        }
        return null;
      })
    ).subscribe((data: any) => {
      if (data) {
        this.publicService.pushUrlData.next(data);
        const currentUrl = this.router.url;
        if (isPlatformBrowser(this.platformId)) {
          sessionStorage.setItem('storedUrl', currentUrl);
        }
        this.checkAndSetLanguageInUrl();
      }
    });
    this.router.events.subscribe(event => {
      if (event instanceof NavigationEnd) {
        this.updateNavigationHistory(event.url);
      }
    });
  }

  private updateNavigationHistory(url: string): void {
    if (isPlatformBrowser(this.platformId) && url !== this.navigationHistory[this.navigationHistory.length - 1]) {
      this.navigationHistory.push(url);
      if (isPlatformBrowser(this.platformId)) {
        localStorage.setItem(keys.navigationHistory, JSON.stringify(this.navigationHistory));
      }
    }
  }

  private setupLanguageSupport(): void {
    this.translateService.addLangs(this.languages);
    this.currentLanguage = this.getLanguageFromLocalStorage();

    if (this.currentLanguage) {
      this.applyLanguage(this.currentLanguage);
    } else {
      this.applyBrowserLanguage();
    }
  }

  private getLanguageFromLocalStorage(): string | null {
    return isPlatformBrowser(this.platformId) ? localStorage.getItem(keys.language) : null;
  }

  private applyLanguage(lang: string): void {
    this.translateService.use(lang);
    this.setLanguageDirection(lang === 'ar' ? 'rtl' : 'ltr');
    this.translateService.stream('primeng').subscribe(data => this.primengConfig.setTranslation(data));
  }

  private applyBrowserLanguage(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.browserLang = this.translateService.getBrowserLang();
      if (this.browserLang) {
        localStorage.setItem(keys.language, this.browserLang);
        this.translateService.use(this.browserLang);
        this.translateService.setDefaultLang(this.browserLang);
        window.location.reload();
      }
    }
  }

  private setLanguageDirection(direction: 'rtl' | 'ltr'): void {
    if (isPlatformBrowser(this.platformId)) {
      this.renderer.setAttribute(this.document.documentElement, 'dir', direction);
      this.renderer.setAttribute(this.document.documentElement, 'lang', this.currentLanguage || '');
      this.renderer.setAttribute(this.document.documentElement, 'class', this.currentLanguage || '');
    }
  }

  private setFavicon(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.favIcon = this.document.querySelector("#appIcon");
      if (this.favIcon) {
        this.favIcon.href = `${environment.imageBaseUrl}/front_assets/imgs/logo.svg`;
      }
    }
  }

  private updateMetaTags(): void {
    this.metadataService.setSharePreviewImage(`${environment.imageBaseUrl}/front_assets/imgs/logo.svg`);
  }

  private loadGlobalCategories(): void {
    this.getPlacesCategories();
    this.getStoresCategories();
    this.getRestaurantCategories();
  }

  private getPlacesCategories(): void {
    const subscription = this.placesService.getCategories().pipe(
      tap(res => this.handleCategoriesResponse(res, this.placesService.categoriesListSubj)),
      catchError(err => this.handleError(err)),
      finalize(() => { })
    ).subscribe();
    this.subscriptions.push(subscription);
  }
  private getStoresCategories(): void {
    const subscription = this.storesService.getStoresCategories().pipe(
      tap(res => this.handleCategoriesResponse(res, this.publicService.storeSubjCategory)),
      catchError(err => this.handleError(err)),
      finalize(() => { })
    ).subscribe();
    this.subscriptions.push(subscription);
  }
  private getRestaurantCategories(): void {
    const subscription = this.restaurantsService.getCategories().pipe(
      tap(res => this.handleCategoriesResponse(res, this.publicService.restaurantSubjCategory)),
      catchError(err => this.handleError(err)),
      finalize(() => { })
    ).subscribe();
    this.subscriptions.push(subscription);
  }
  private handleCategoriesResponse(res: any, subject: any): void {
    if (res.code === 200) {
      subject.next(res?.data || []);
    } else {
      this.handleError(res?.message);
    }
  }

  private handleError(error: any): any {
    const message = error?.message || 'An error has occurred';
    this.alertsService.openToast('error', message);
  }
}
