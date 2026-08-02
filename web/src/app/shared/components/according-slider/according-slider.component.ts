import { Component, inject, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Component({
  selector: 'app-according-slider',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './according-slider.component.html',
  styleUrls: ['./according-slider.component.scss']
})
export class AccordingSliderComponent {
  currentLanguage: string;
  activeIndex: number = 0;

  private platformId = inject(PLATFORM_ID)
  private publicService = inject(PublicService)

  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
  }
  images = [
    'https://i.pinimg.com/564x/59/b8/1b/59b81b1566ede55ac307899a39f637e8.jpg',
    'https://media.istockphoto.com/id/487549115/photo/himalayan-mountains-view-from-mt-shivapuri.jpg?s=612x612&w=0&k=20&c=fY-JxlccXQCe3BFvi1cMcoZXn0K3KCcI817qqaW9M1k=',
    'https://static.vecteezy.com/system/resources/thumbnails/042/724/900/small/snow-covered-mountain-under-cloudy-sky-photo.jpeg',
    'https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg'
  ];

  setActive(index: number) {
    this.activeIndex = index;
  }
}
