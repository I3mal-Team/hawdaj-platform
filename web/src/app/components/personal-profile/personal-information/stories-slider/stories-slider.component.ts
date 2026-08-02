import { UploadImageVideoFromServerComponent } from './upload-image-video-from-server/upload-image-video-from-server.component';
import { StoryImageVideoModalComponent } from './story-image-video-modal/story-image-video-modal.component';
import { ProfileService } from '../../../../services/profile.service';
import { PublicService } from './../../../../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { Subscription, catchError, finalize, tap } from 'rxjs';
import { IStory } from './../../../../interfaces/profile';
import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { CarouselModule } from 'primeng/carousel';
import { CommonModule } from '@angular/common';
import { ImageModule } from 'primeng/image';

@Component({
  selector: 'app-stories-slider',
  standalone: true,
  imports: [CommonModule, TranslateModule, CarouselModule, ImageModule],
  templateUrl: './stories-slider.component.html',
  styleUrls: ['./stories-slider.component.scss']
})
export class StoriesSliderComponent {
  private subscriptions: Subscription[] = [];

  @Input() items: IStory[] = [];
  @Output() addNewStoryHandler = new EventEmitter();
  storyData: any;

  responsiveOptions = [
    {
      breakpoint: '1400px',
      numVisible: 7,
      numScroll: 1
    },
    {
      breakpoint: '1024px',
      numVisible: 6,
      numScroll: 1
    },
    {
      breakpoint: '992px',
      numVisible: 5,
      numScroll: 1
    },
    {
      breakpoint: '768px',
      numVisible: 4,
      numScroll: 1
    },
    {
      breakpoint: '560px',
      numVisible: 3,
      numScroll: 1
    },
    {
      breakpoint: '450px',
      numVisible: 2,
      numScroll: 1
    }
  ];

  constructor(
    private profileService: ProfileService,
    private alertsService: AlertsService,
    private dialogService: DialogService,
    private publicService: PublicService,
  ) { }

  ngOnInit(): void {
  }

  getClass(): any {
    if (this.items?.length <= 1) {
      return 'd-flex justify-content-start'
    }
  }

  // Start Upload New Story
  uploadStory(): void {
    const ref = this.dialogService?.open(UploadImageVideoFromServerComponent, {
      header: this.publicService?.translateTextFromJson('profile.addNewStory'),
      dismissableMask: false,
      width: '35%',
      height: '70%',
      styleClass: 'custom-modal',
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
        this.storyData = res;
        this.addNewStory();
      }
    });
  }
  // End Upload New Story


  // Start Show Story
  show(item: IStory): void {
    const ref = this.dialogService?.open(StoryImageVideoModalComponent, {
      header: ' ',
      data: item,
      dismissableMask: false,
      width: '100%',
      height: '100%',
      styleClass: 'custom-modal story-modal',
    });
    ref.onClose.subscribe((res: any) => {
      this.storyData = res;
    });
  }
  // End Show Story

  // Start Upload Sotory Functions
  addNewStory(): void {
    this.publicService.show_loader.next(true);
    let formData = new FormData();
    formData.append('type', 'file');
    formData.append('file', this.storyData?.file);
    formData.append('text', this.storyData?.storyName);
    // formData.append('storyText', this.storyData?.storyName);
    let addStorySubscribe: Subscription = this.profileService?.addNewStory(formData).pipe(
      tap(res => this.handleAddStorySuccess(res)),
      catchError(err => this.handleError(err)),
      finalize(() => this.finalizeLoading())
    ).subscribe();
    this.subscriptions.push(addStorySubscribe);
  }
  private handleAddStorySuccess(response: any): void {
    if (response?.code == 200) {
      this.handleSuccess(response?.message);
      this.addNewStoryHandler.emit({ isAddNewStory: true });
    } else {
      this.handleError(response?.message);
    }
  }
  private finalizeLoading(): void {
    this.publicService.show_loader.next(false);
  }
  // End Upload Sotory Functions


  // Handle Api Errors Or Success
  private handleError(error: any): any {
    error ? this.alertsService?.openToast('error', error || this.publicService.translateTextFromJson('general.errorOccur')) : '';
    this.publicService.show_loader.next(false);
  }
  private handleSuccess(msg: any): void {
    msg ? this.alertsService?.openToast('success', msg) : '';
    this.publicService.show_loader.next(false);
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
