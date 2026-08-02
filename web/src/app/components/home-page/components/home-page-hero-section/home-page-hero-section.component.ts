import { Component, Input, Inject, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { LazyLoadDirective } from 'src/app/shared/directives/lazy-load.directive';
import { QuickGlobalSearchComponent } from '../quick-global-search/quick-global-search.component';
import { Skeleton } from 'primeng/skeleton';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { Subscription } from 'rxjs';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Component({
  selector: 'app-home-page-hero-section',
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    LazyLoadSectionDirective,
    LazyLoadDirective,
    QuickGlobalSearchComponent,
    SkeletonComponent,
    NgOptimizedImage
  ],
  templateUrl: './home-page-hero-section.component.html',
  styleUrls: ['./home-page-hero-section.component.scss']
})
export class HomePageHeroSectionComponent {
  private subscriptions: Subscription[] = [];
  isBrowser: boolean;
  @Input() startTrip!: () => void;
  showMap: boolean = false;

  @Input() defaultTags: any;
  @Input() currentLanguage: any;
  @Input() isLoadingHomeData: any;
  globalSearchSectionInView = false;

  constructor(
    private dialogService: DialogService,
    private publicService: PublicService,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {
    this.isBrowser = isPlatformBrowser(this.platformId);
  }

  ngOnInit() {
    if (this.isBrowser) {
      this.showMap = false;
    }
  }

  explore(): void {
    if (this.isBrowser) {
      this.publicService?.showMap?.next(true);
      let showMapSubscription: Subscription = this.publicService?.showMap?.subscribe(res => {
        this.showMap = res;
      });
      this.subscriptions.push(showMapSubscription);
    }
  }
  
  ngOnDestroy(): void {
    this.subscriptions.forEach(subscription => {
      if (subscription && !subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
