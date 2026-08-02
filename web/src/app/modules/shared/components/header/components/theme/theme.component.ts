import { isPlatformBrowser } from '@angular/common';
import { keys } from './../../../../configs/localstorage-key';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { ThemeService } from 'src/app/services/themes/theme.service';

@Component({
  selector: 'app-theme',
  templateUrl: './theme.component.html',
  styleUrls: ['./theme.component.scss']
})
export class ThemeComponent {
  currentTheme: any;
  theme: any = "true";

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    public themeService: ThemeService
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.theme = window?.localStorage?.getItem(keys?.theme);
    }
  }

  light(): void {
    this.themeService?.setLightTheme();
    if (isPlatformBrowser(this.platformId)) {
      this.currentTheme = window?.localStorage?.getItem(keys?.theme);
    }
  }
  dark(): void {
    this.themeService?.setDarkTheme();
    if (isPlatformBrowser(this.platformId)) {
      this.currentTheme = window?.localStorage?.getItem(keys?.theme);
    }
  }
  color(color: string): void {
    this.themeService?.setColorTheme(color);
    if (isPlatformBrowser(this.platformId)) {
      this.currentTheme = window?.localStorage?.getItem(keys?.theme);
    }
  }
}
