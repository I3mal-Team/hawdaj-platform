import { TranslationChildModule } from '../../../services/translation-child.module';
import { ChangeDetectorRef, Component, EventEmitter, Inject, Input, Output, PLATFORM_ID } from '@angular/core';
import { PublicService } from '../../../modules/shared/services/public.service';
import { environment } from '../../../../environments/environment';
import { keys } from '../../../modules/shared/configs/localstorage-key';
import { DialogService } from 'primeng/dynamicdialog';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { RatingModule } from 'primeng/rating';
import { FormsModule } from '@angular/forms';
import { Subscription } from 'rxjs';
import { ReviewsCarouselComponent } from 'src/app/shared/components/reviews-carousel/reviews-carousel.component';
import { RateComponent } from 'src/app/components/events/components/rate/rate.component';
@Component({
  standalone: true,
  imports: [CommonModule, TranslationChildModule, FormsModule, RatingModule, ReviewsCarouselComponent],
  selector: 'app-review-event-slider',
  templateUrl: './review-event-slider.component.html',
  styleUrls: ['./review-event-slider.component.scss']
})
export class ReviewEventSliderComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;

  @Input() dataArray: any;
  @Input() parentId: any;
  @Output() emitServiceAddNew = new EventEmitter();
  testimonials: any = [];

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private dialogService: DialogService,
    private publicService: PublicService,
    private cdr: ChangeDetectorRef
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.cdr.detectChanges();
  }

  leaveReview(): void {
    const ref = this.dialogService.open(RateComponent, {
      header: this.publicService?.translateTextFromJson('general.rate'),
      width: '45%',
      baseZIndex: 10000,
      data: {
        type: 'events',
        parentId: this.parentId
      },
      styleClass: 'rate'
    });
    ref.onClose.subscribe((res: any) => {
      if (res?.isAddReview) {
        this.emitServiceAddNew.next(true);
      }
    })
  }

  ngOnDestroy(): void {
    this.unsubscribe.forEach((sb) => sb.unsubscribe());
  }
}
