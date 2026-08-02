import { AlertsService } from 'src/app/services/alerts.service';
import { ProfileService } from '../../../../../services/profile.service';
import { ShareComponent } from './../../../../../modules/shared/components/share/share.component';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { DynamicDialogConfig, DialogService } from 'primeng/dynamicdialog';
import { IStory } from './../../../../../interfaces/profile';
import { DomSanitizer } from '@angular/platform-browser';
import { ProgressBarModule } from 'primeng/progressbar';
import { TranslateModule } from '@ngx-translate/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Component } from '@angular/core';
import { Subscription, catchError, finalize, tap } from 'rxjs';

@Component({
  selector: 'app-story-image-video-modal',
  standalone: true,
  imports: [CommonModule, FormsModule, TranslateModule, ProgressBarModule],
  templateUrl: './story-image-video-modal.component.html',
  styleUrls: ['./story-image-video-modal.component.scss']
})
export class StoryImageVideoModalComponent {
  private subscriptions: Subscription[] = [];

  comment: string;
  showInput: boolean = false;
  progressValue: number = 0;
  progress: any;
  url: any = '';
  data: IStory;

  isLoading: boolean = false;

  constructor(
    private profileService: ProfileService,
    private publicService: PublicService,
    private dialogService: DialogService,
    private config: DynamicDialogConfig,
    private alertsService: AlertsService,
    private sanitizer: DomSanitizer,
  ) { }

  ngOnInit(): void {
    this.data = this.config.data;
    this.url = this.sanitizer.bypassSecurityTrustResourceUrl(this.data?.url);
    this.move();
  }
  move() {
    const id :any= setInterval(() => this.frame(id), 10);
  }

  frame(id: number) {
    if (this.progressValue === 100) {
      clearInterval(id);
    } else {
      this.progressValue++;
    }
  }
  addComment(): void {
    this.showInput = true;
  }

  cancelComment(): void {
    this.comment = '';
    this.showInput = false;
  }

  share(): void {
    const ref = this.dialogService.open(ShareComponent, {
      header: this.publicService?.translateTextFromJson('general.share'),
      width: '40%',
      baseZIndex: 10000,
      data: {
        link: this.data.file,
      },
      styleClass: 'rate',
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
      }
    });
  }

  // Start Add Comment
  sendComment(): void {
    this.isLoading = true;
    let formData = new FormData();
    formData.append('comment', this.comment);
    let addStorySubscribe: Subscription = this.profileService?.addCommentToStory(formData).pipe(
      tap(res => this.handleAddCommentSuccess(res)),
      catchError(err => this.handleError(err)),
      finalize(() => this.finalizeLoading())
    ).subscribe();
    this.subscriptions.push(addStorySubscribe);
  }
  private handleAddCommentSuccess(response: any): void {
    if (response?.code == 200) {
      this.handleSuccess(response?.message);
      this.showInput = false;
      this.comment = '';
    } else {
      this.handleError(response?.message);
    }
  }
  private finalizeLoading(): void {
    this.isLoading = false;
    this.showInput = false;
    this.comment = '';
  }
  // End Add Comment


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
