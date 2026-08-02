import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { PublicService } from './../../../modules/shared/services/public.service';
import { DynamicDialogRef, DynamicDialogConfig } from 'primeng/dynamicdialog';
import { TranslateModule } from '@ngx-translate/core';
import { DropdownModule } from 'primeng/dropdown';
import { Component, OnInit, computed, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SvgIconComponent } from 'src/app/shared/components/svg-icon/svg-icon.component';
import { phoneOrUrlValidator, urlValidator } from 'src/app/domains/profile-settings/features/my-properties/create-update-property-item/property-form.validators';

@Component({
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule, TranslateModule, DropdownModule, CommonModule, SvgIconComponent],
  selector: 'app-add-contact-info-modal',
  templateUrl: './add-contact-info-modal.component.html',
  styleUrls: ['./add-contact-info-modal.component.scss']
})
export class AddContactInfoModalComponent implements OnInit {
  socialOptions: any = [];
  dataModal: any;
  isEdit: Boolean = false;
  item: any;

  addContactForm: any = this.formBuilder.group(
    {
      name: [null, { validators: [Validators.required] }],
      link: ['', { 
        validators: [Validators.required], 
        updateOn: 'blur' 
      }]
    },
  );

  readonly selectedPlatform = computed(() => this.addContactForm.value.name);
  readonly isWhatsApp = computed(() => this.selectedPlatform()?.id === 'whatsapp');

  get addContactFormControls(): any {
    return this.addContactForm?.controls;
  }

  constructor(
    public publicService: PublicService,
    private config: DynamicDialogConfig,
    private formBuilder: FormBuilder,
    private ref: DynamicDialogRef
  ) { }

  ngOnInit(): void {
    this.socialOptions = this.publicService.getSocialOptions();
    // Add WhatsApp option
    this.socialOptions?.push({ id: 'whatsapp', title: 'WhatsApp' });
    
    // Add personal_account if type is tourGuide
    if (this.config?.data?.type == 'tourGuide') {
      this.socialOptions?.push({ id: 'personal_account', title: 'Personal Site' });
    } else {
      this.socialOptions?.push({ id: 'tiktok', title: 'tiktok' });
    }
    
    this.dataModal = this.config?.data;
    if (this.dataModal?.isEdit) {
      this.isEdit = true;
      this.item = this.dataModal?.el;
      if (this.socialOptions?.length > 0) {
        this.addContactForm.patchValue({
          name: this.item.name,
          link: this.item?.link
        });
      }
    }

    // Update link validator when platform changes
    this.addContactFormControls.name.valueChanges.subscribe(() => {
      this.updateLinkValidator();
    });

    // Set initial validator
    this.updateLinkValidator();
  }

  private updateLinkValidator(): void {
    const isWhatsApp = this.isWhatsApp();
    const linkControl = this.addContactFormControls.link;

    if (isWhatsApp) {
      linkControl.setValidators([Validators.required, phoneOrUrlValidator()]);
    } else {
      linkControl.setValidators([Validators.required, urlValidator()]);
    }

    linkControl.updateValueAndValidity({ emitEvent: false });
  }

  clearSelection(): void {
    this.addContactForm.patchValue({
      name: null
    });
  }

  clearControl(controlName: string, event: Event): void {
    event?.stopPropagation();
    if (controlName === 'name') {
      this.clearSelection();
    } else if (controlName === 'link') {
      this.addContactForm.patchValue({ link: '' });
      this.addContactFormControls.link.markAsDirty();
      this.addContactFormControls.link.markAsTouched();
    }
  }

  addLink(): void {
    if (this.addContactForm?.valid) {
      this.ref.close({ item: this.addContactForm?.value });
    } else {
      this.addContactForm.markAllAsTouched();
      this.publicService?.validateAllFormFields(this.addContactForm);
    }
  }

  onCancel(): void {
    this.ref.close();
  }

  shouldShowError(controlName: string): boolean {
    const control = this.addContactFormControls[controlName];
    return !!control && control.invalid && (control.dirty || control.touched);
  }

  getErrorMessage(controlName: string): string {
    const control = this.addContactFormControls[controlName];
    if (!control || !control.errors) {
      return '';
    }

    if (control.errors['required']) {
      return 'validations.required_field';
    }

    if (control.errors['invalidUrl']) {
      return 'properties.socialMedia.errors.invalidUrl';
    }

    if (control.errors['invalidPhoneOrUrl']) {
      return 'properties.errors.invalidPhoneOrUrl';
    }

    return '';
  }
}

