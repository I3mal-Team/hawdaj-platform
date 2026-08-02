import { PublicService } from 'src/app/modules/shared/services/public.service';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { TranslateModule } from '@ngx-translate/core';
import { Component, Input } from '@angular/core';
import { Router } from '@angular/router';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-event-card-home',
  standalone: true,
  imports: [CommonModule, TranslateModule, NgOptimizedImage, StripHtmlPipe],
  templateUrl: './event-card-home.component.html',
  styleUrls: ['./event-card-home.component.scss']
})
export class EventCardHomeComponent {

  @Input() item: any;
  currentLanguage: string = '';

  constructor(
    private publicService: PublicService,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }

  showDetails(item: any): void {
    if (item?.slug) {
      this.router.navigate(['/events/event-details/' + item?.slug])
    }
  }
}
