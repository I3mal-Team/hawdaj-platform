import { ApplicationCardHomeComponent } from './application-card-home/application-card-home.component';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { IAppItem } from 'src/app/interfaces/home';
import { Component, Input } from '@angular/core';
import { RouterModule } from '@angular/router';
import { CarouselModule } from 'primeng/carousel';

@Component({
  selector: 'app-apps-home-section',
  standalone: true,
  imports: [CommonModule,RouterModule,TranslateModule,CarouselModule,NgOptimizedImage,ApplicationCardHomeComponent],
  templateUrl: './apps-home-section.component.html',
  styleUrls: ['./apps-home-section.component.scss']
})
export class AppsHomeSectionComponent {
  @Input() items: IAppItem[] = [];  // This should be populated with the actual interface structure.

  constructor(
    public _PublicService:PublicService
  ) {}
}
