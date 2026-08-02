import { LocalizationLanguageService } from '../../services/localization-language.service';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { TranslationService } from 'src/app/services/translation.service';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { PublicService } from '../../services/public.service';

@Component({
  selector: 'app-change-language-selector',
  standalone: true,
  imports: [CommonModule, NgOptimizedImage],
  templateUrl: './change-language-selector.component.html',
  styleUrls: ['./change-language-selector.component.scss']
})
export class ChangeLanguageSelectorComponent {
  currentLanguage: string | null = '';
  language: string = '';
  page: string = '';
  collapse: boolean = false;

  constructor(
    private _LocalizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    public translationService: TranslationService,
    private publicService: PublicService
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
      this.publicService?.pushUrlData?.subscribe((res: any) => {
        this.page = res.page;
      });
    }
  }

  chnageLanguage(lang: any): void {
    if (isPlatformBrowser(this.platformId)) {
    this._LocalizationLanguageService.updatePathAccordingLang(lang);
    this.translationService.changeLang(lang);
    }
  }
  shouldApplyDarkToggle(): boolean {
    const includedPages = [
      // 'applications',
      // 'tourGuides',
      // 'place-details',
      // 'store-details',
      // 'restaurants',
      // 'events-details',
      // 'tourGuideInfo'
    ];
    return includedPages.includes(this.page);
  }
}
