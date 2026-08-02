// Modules
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { CommonModule } from '@angular/common';
import { ToastModule } from 'primeng/toast';

// Components
import { FileUploadComponent } from '../../../../modules/shared/components/file-upload/file-upload.component';
// Services

import { ProfileService } from '../../../../services/profile.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { Subscription, catchError, tap } from 'rxjs';
import { Component } from '@angular/core';

@Component({
  selector: 'app-add-new-landmark',
  standalone: true,
  imports: [CommonModule, FormsModule, ReactiveFormsModule, TranslateModule, FileUploadComponent, ToastModule],
  templateUrl: './add-new-landmark.component.html',
  styleUrls: ['./add-new-landmark.component.scss']
})
export class AddNewLandmarkComponent {
  private subscriptions: Subscription[] = [];

  landmarkType: string;
  image: any = null;

  constructor(
    private profileService: ProfileService,
    private alertsService: AlertsService,
    public publicService: PublicService,
    private config: DynamicDialogConfig,
    private ref: DynamicDialogRef,
    private fb: FormBuilder,
  ) { }

  landmarkForm = this.fb?.group({
    title: ['', { validators: [Validators.required], updateOn: 'blur' }],
    description: ['', { validators: [Validators.required], updateOn: 'blur' }],
    address: ['', { validators: [Validators.required], updateOn: 'blur' }],
    image: [null, { validators: [Validators.required] }],
  });
  get formControls(): any {
    return this.landmarkForm?.controls;
  }

  ngOnInit(): void {
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
    this.landmarkType = this.config.data;
  }

  uploadImg(event: any): void {
    this.image = event.file;
    this.landmarkForm.get('image').setValue(this.image);
  }

  // Start Add Landmark
  submit(): void {
    if (this.landmarkForm?.valid) {
      const formData: any = this.extractFormData();
      this.addLandmark(formData);
    } else {
      this.publicService?.validateAllFormFields(this.landmarkForm);
    }
  }
  private extractFormData(): any {
    let formData = new FormData();
    formData.append('title', this.landmarkForm?.value?.title);
    formData.append('description', this.landmarkForm?.value?.description);
    formData.append('address', this.landmarkForm?.value?.address);
    formData.append('image', this.landmarkForm?.value?.image);
    formData.append('type', this.landmarkType);
    return formData;
  }
  private addLandmark(formData: any): void {
    this.publicService?.show_loader?.next(true);
    let subscribeAddLandmark: Subscription = this.profileService?.addLandmark(formData).pipe(
      tap(res => this.handleAddLandmarkSuccess(res)),
      catchError(err => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(subscribeAddLandmark);
  }
  private handleAddLandmarkSuccess(response: any): void {
    this.publicService?.show_loader?.next(false);
    if (response?.code == 200) {
      this.ref.close({ listChanged: true, item: response?.data });
      this.handleSuccess(response?.message);
      this.publicService.recallProfileDataFuntion.next(true);
    } else {
      this.handleError(response?.message);
    }
  }
  // End Add Landmark

  /* --- Handle api requests messages --- */
  private handleSuccess(msg: any): any {
    this.setMessage(msg || this.publicService.translateTextFromJson('general.successRequest'), 'success');
  }
  private handleError(err: any): any {
    this.setMessage(err || this.publicService.translateTextFromJson('general.errorOccur'), 'error');
  }
  private setMessage(message: string, type: string): void {
    this.alertsService.openToast(type, message);
    this.publicService?.show_loader?.next(false);
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && !subscription?.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
