import { Inject, Injectable, PLATFORM_ID } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { isPlatformBrowser } from '@angular/common';
import { Subject } from 'rxjs';

import { TransferState, makeStateKey } from '@angular/platform-browser';
import { Router } from '@angular/router';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';

const LANG_KEY = makeStateKey<string>('LANGUAGE');

@Injectable({
  providedIn: 'root'
})
export class TranslationService {
  currentLang: string = 'en';
  localeEvent = new Subject<string>();

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private transferState: TransferState,
    private translate: TranslateService,
    private router: Router
  ) {
    if (isPlatformBrowser(platformId)) {
      this.currentLang = localStorage.getItem(keys.language) || this.transferState.get(LANG_KEY, this.translate.getDefaultLang());
      this.translate.use(this.currentLang);
      this.metadataService.updateMetaAccordingCurrentLanguage();
    }
  }

  /**
   * Change language
   */

  changeLang(lang: string) {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLang = localStorage.getItem(keys.language);
      if (this.currentLang !== lang) {
        localStorage.setItem(keys.language, lang);
        window.location.reload();
        window?.scrollTo(0, 0);
      }
      setTimeout(() => {
        this.translate.use(lang);
        this.localeEvent.next(lang);

        let direction =
          localStorage.getItem(keys.language) === "ar"
            ? "rtl"
            : "ltr";
        document.documentElement.dir = direction;
        document.documentElement.lang = this.currentLang;

        let getMain = document.getElementsByTagName("html")[0];
        getMain.setAttribute("lang", this.currentLang);
        getMain.setAttribute("class", this.currentLang);
      }, 500);
      this.metadataService.updateMetaAccordingCurrentLanguage();
    }
  }


  /**
   * Returns selected language
   */
  getSelectedLanguage(): string {
    if (isPlatformBrowser(this.platformId)) {
      return localStorage.getItem(keys.language) || this.translate.getDefaultLang();
    }
    return this.currentLang;
  }
}
