import { Injectable, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { keys } from '../configs/localstorage-key';
import { Location } from '@angular/common';
import { Router } from '@angular/router';
import { TranslationService } from 'src/app/services/translation.service';
import { PublicService } from './public.service';

@Injectable({
  providedIn: 'root'
})
export class LocalizationLanguageService {
  private currentLanguage: string;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private _TranslationService: TranslationService,
    private publicService: PublicService,
    private location: Location,
    private router: Router
  ) {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage(); // Default to 'en' if not set
    } else {
      this.currentLanguage = 'ar'; // Default language for server-side
    }
  }

  updatePathAccordingLangOldCode(lang?: any): string {
    const currentUrl = this.getCurrentUrl();
    const currentUrlSegments = currentUrl.startsWith('/') ? currentUrl.substring(1).split('/') : currentUrl.split('/');
    // if (lang) {
    //   if (['ar', 'en', 'ru', 'zh'].includes(lang)) {
    //     currentUrlSegments[0] = lang;
    //   } else {
    //     currentUrlSegments.unshift(this.getCurrentLanguage());
    //   }
    // } else {
    //   if (['ar', 'en', 'ru', 'zh'].includes(currentUrlSegments[0])) {
    //     this._TranslationService.changeLang(currentUrlSegments[0]);
    //   } else {
    //     currentUrlSegments.unshift(this.getCurrentLanguage());
    //   }
    // }
    // Join the segments back into a new path
    let newPath = '/' + currentUrlSegments.join('/');
    this.updatePath(newPath);
    return newPath;
  }
  updatePathAccordingLang(lang?: any): string {
    const currentUrl = this.getCurrentUrl();
    const currentUrlSegments = currentUrl.startsWith('/') ? currentUrl.substring(1).split('/') : currentUrl.split('/');
    if (lang) {
      if (['ar', 'en', 'ru', 'zh'].includes(lang)) {
        currentUrlSegments[0] = lang;
      } else {
        currentUrlSegments.unshift(this.getCurrentLanguage());
      }
    } else {
      if (['ar', 'en', 'ru', 'zh'].includes(currentUrlSegments[0])) {
        // currentUrlSegments[0] = this.getCurrentLanguage();
        this._TranslationService.changeLang(currentUrlSegments[0]);
      } else {
        currentUrlSegments.unshift(this.getCurrentLanguage());
      }
    }
    // Join the segments back into a new path
    let newPath = '/' + currentUrlSegments.join('/');
    this.updatePath(newPath);
    return newPath;
  }

  getFullURL(): string {
    return this.updatePathAccordingLang();
  }

  getCurrentLanguage(): string {
    return this.currentLanguage;
  }
  getCurrentUrl(): string {
    return this.location.path();
    // return this.router.url;
  }
  updatePath(newPath: string): void {
    this.location.replaceState(newPath);
  }

}
