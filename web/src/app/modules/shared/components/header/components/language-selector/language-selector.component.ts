import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { ResponsiveIfDirectiveNotStandalone } from 'src/app/shared/directives/appResponiveIf.direcitve';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { TranslationService } from 'src/app/services/translation.service';
import { PublicService } from './../../../../services/public.service';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { keys } from './../../../../configs/localstorage-key';
import { TranslateModule } from '@ngx-translate/core';
import { RouterModule } from '@angular/router';


@Component({
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    TranslateModule,
    ResponsiveIfDirectiveNotStandalone,
    NgOptimizedImage
  ],
  selector: 'app-language-selector',
  templateUrl: './language-selector.component.html',
  styleUrls: ['./language-selector.component.scss']
})
export class LanguageSelectorComponent {
  currentLanguage: any;
  language: string = '';
  page: any;

  constructor(
    private _LocalizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    public translationService: TranslationService,
    private publicService: PublicService
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.publicService?.pushUrlData?.subscribe((res: any) => {
      this.page = res.page;
    })
  }

  chnageLanguage(lang: any): void {
    this._LocalizationLanguageService.updatePathAccordingLang(lang);
    this.translationService.changeLang(lang);
  }
}
