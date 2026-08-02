import { DialogService, DynamicDialogRef } from 'primeng/dynamicdialog';
import { LoginComponent } from '../authentication/components/login/login.component';
import { Component, OnInit, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { LoginPopupComponent } from '../authentication/components/login-popup/login-popup.component';

@Component({
  standalone: true,
  imports: [],
  selector: 'app-join-us',
  templateUrl: './join-us.component.html',
  styleUrls: ['./join-us.component.scss']
})
export class JoinUsComponent implements OnInit {

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private publicService: PublicService,
    private dialogService: DialogService,
    private ref: DynamicDialogRef,
  ) { }

  ngOnInit(): void {
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
    // Perform any initialization tasks here
  }

  login(): void {
    // Close the current dialog if open
    if (this.ref) {
      this.ref.close();
    }

    // Open login dialog only if on browser platform
    if (isPlatformBrowser(this.platformId)) {
      const ref = this.dialogService.open(LoginPopupComponent, {
        width: '60%',
        height: '700px', styleClass: 'auth-dialog',
      });
    }
  }
}
