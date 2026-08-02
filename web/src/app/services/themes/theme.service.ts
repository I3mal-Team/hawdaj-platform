import { keys } from "src/app/modules/shared/configs/localstorage-key";
import { Theme, light, dark } from "./theme";
import { Inject, Injectable, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from "@angular/common";

@Injectable({
  providedIn: 'root'
})
export class ThemeService {
  constructor(@Inject(PLATFORM_ID) private platformId: Object) { }
  private active: Theme = light;
  private availableThemes: Theme[] = [light, dark];

  getAvailableThemes(): Theme[] {
    return this.availableThemes;
  }

  getActiveTheme(): Theme {
    return this.active;
  }

  setLightTheme(): void {
    if (isPlatformBrowser(this.platformId)) {
    this.setActiveTheme(light);
    localStorage.setItem(keys?.theme, 'light');
  }
  }
  setDarkTheme(): void {
    if (isPlatformBrowser(this.platformId)) {
    this.setActiveTheme(dark);
    localStorage.setItem(keys?.theme, 'dark');
    }
  }

  setColorTheme(color: any): void {
    if (isPlatformBrowser(this.platformId)) {
    Object.keys(this.active.properties).forEach((property) => {
      if (property == '--text-main-color' || property == '--bg-main-color') {
        this.active.properties[property] = color;
      }
      document.documentElement.style.setProperty(
        property,
        this.active.properties[property]
      );
    });
    
    localStorage.setItem(keys?.theme, 'light');
  }
  }

  setActiveTheme(theme: Theme): void {
    this.active = theme;
    if (isPlatformBrowser(this.platformId)) {
    Object.keys(this.active.properties).forEach((property) => {
      document.documentElement.style.setProperty(
        property,
        this.active.properties[property]
      );
    });
  }
}
}
