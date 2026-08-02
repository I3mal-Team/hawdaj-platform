import { EventCardHomeComponent } from './event-card-home/event-card-home.component';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { IAppItem } from 'src/app/interfaces/home';
import { Component, Input } from '@angular/core';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-events-home-section',
  standalone: true,
  imports: [CommonModule,RouterModule,TranslateModule,NgOptimizedImage,EventCardHomeComponent],
  templateUrl: './events-home-section.component.html',
  styleUrls: ['./events-home-section.component.scss']
})
export class EventsHomeSectionComponent {
  @Input() items: any[] = [];  // This should be populated with the actual interface structure.

  constructor(
    public _PublicService:PublicService
  ) {}
}
