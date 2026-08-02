import { Component, inject, Input, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { ComingSoonModalComponent } from 'src/app/modules/shared/components/coming-soon-modal/coming-soon-modal.component';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { DialogService } from 'primeng/dynamicdialog';
import { SkeletonComponent } from "../../../../modules/shared/components/skeleton/skeleton.component";
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';

@Component({
  selector: 'app-chat',
  standalone: true,
  imports: [CommonModule, TranslateModule, SkeletonComponent, ReactiveFormsModule],
  templateUrl: './chat.component.html',
  styleUrls: ['./chat.component.scss']
})
export class ChatComponent {

  @Input() title: string = 'places.hawdajBot';
  @Input() profileImage: string = 'assets/images-v2/pages/place-details/hawdaj-bot-online.svg';
  @Input() chatImg: string = 'assets/images-v2/pages/place-details/hawdaj-bot.png';
  @Input() commingSoonImage: string = 'assets/images-v2/pages/place-details/hawdaj-bot.png';
  private platformId = inject(PLATFORM_ID);
  public publicService = inject(PublicService)
  private dialogService = inject(DialogService);
  private fb = inject(FormBuilder);


  chat: any = [];
  isLoadingChat: boolean = false;
  selectedFile: any = '';
  chatForm = this.fb.group({
    message: ['', { validators: [Validators.required], updateOn: 'change' }],
  });
  fullUrl: any = null;
  displayChat: boolean = false;


  openComingSoon(): void {
    const ref = this?.dialogService?.open(ComingSoonModalComponent, {
      width: '35%',
      styleClass: '',
      header: '',
      dismissableMask: true,
      data: {
        image: this.commingSoonImage,
      }
    });
  }
  onFileSelected(event: any): void {
    this.selectedFile = event.target.files[0] ?? null;
    if (this.selectedFile.size <= 5000) {
      this.chatForm.patchValue({
        message: this.selectedFile.name,
      });
      // this.chatForm.setValue()
    }
  }
  addToChat(e: any, message: any): void {
    this.chat.push({ text: message?.value, type: 'two' }), (message.value = '');
    setTimeout(() => {
      this.chat.push({ text: 'Thank You', type: 'one' });
    }, 5000);
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo(e.yPosition);
    }
  }
}
