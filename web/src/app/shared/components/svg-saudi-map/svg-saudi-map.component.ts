import { Component, ElementRef, EventEmitter, Inject, Input, Output, PLATFORM_ID, ViewChild } from '@angular/core';
import { TranslationChildModule } from '../../../services/translation-child.module';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { keys } from '../../../modules/shared/configs/localstorage-key';
import { PublicService } from '../../../modules/shared/services/public.service';

@Component({
  standalone: true,
  imports: [TranslationChildModule, CommonModule],
  selector: 'app-svg-saudi-map',
  templateUrl: './svg-saudi-map.component.html',
  styleUrls: ['./svg-saudi-map.component.scss']
})
export class SvgSaudiMapComponent {

  @Input() defaultCityColor: any;
  @Input() selectedCityColor: any;
  @Output() emitCityAreaData = new EventEmitter();

  @ViewChild('mapSvg', { static: false }) mapSvg!: ElementRef<SVGElement>;
  currentLanguage: any;
  @Input() isNew: boolean = false;
  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private publicService: PublicService
  ) {

  }
  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
  }
  zoomOut(): void {
    const svgElement = this.mapSvg.nativeElement;
    const viewBoxValue = svgElement.getAttribute('viewBox');
    if (!viewBoxValue) {
      console.error("SVG doesn't have a viewBox attribute!");
      return;
    }
    const viewBox = viewBoxValue.split(' ').map(Number);
    const zoomFactor = 1.2;
    const newWidth = viewBox[2] * zoomFactor;
    const newHeight = viewBox[3] * zoomFactor;
    const newViewBox = [viewBox[0] - (newWidth - viewBox[2]) / 2, viewBox[1] - (newHeight - viewBox[3]) / 2, newWidth, newHeight];
    svgElement.setAttribute('viewBox', newViewBox.join(' '));
  }
  zoomIn(): void {
    const svgElement = this.mapSvg.nativeElement;
    const viewBoxValue = svgElement.getAttribute('viewBox');
    if (!viewBoxValue) {
      // console.error("SVG doesn't have a viewBox attribute!");
      return;
    }
    const viewBox = viewBoxValue.split(' ').map(Number);
    const zoomFactor = 1.2;
    const newWidth = viewBox[2] / zoomFactor;
    const newHeight = viewBox[3] / zoomFactor;
    const newViewBox = [viewBox[0] + (viewBox[2] - newWidth) / 2, viewBox[1] + (viewBox[3] - newHeight) / 2, newWidth, newHeight];
    svgElement.setAttribute('viewBox', newViewBox.join(' '));
  }

  selectedCity: string | null = null;
  @Input() selectedCityId: string | null = null
  @Input() selectedCityId2: string | null = null
  selectedCityLat: string | null = null
  selectedCityLong: string | null = null
  onRegionClick(event: any): void {
    if (event.target.id) {
      this.selectedCity = event?.target;
      this.selectedCityId = event?.target?.id;
      // Access and print the data-lng and data-lat attributes
      this.selectedCityLat = event?.target?.getAttribute('data-lat');
      this.selectedCityLong = event?.target?.getAttribute('data-lng');

    } else {
      this.selectedCity = null;
      this.selectedCityId = null;
    }
    let data: any = {
      id: this.selectedCityId,
      lat: this.selectedCityLat,
      long: this.selectedCityLong
    }
    this.emitCityAreaData.next({
      selectedRegionInfo: data
    });
  }

}
