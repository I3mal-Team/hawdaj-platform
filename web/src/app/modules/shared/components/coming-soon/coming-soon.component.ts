import { PublicService } from '../../services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { patterns } from '../../configs/patternValidation';
import { keys } from '../../configs/localstorage-key';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { HomeService } from '../../../../services/home.service';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Subscription } from 'rxjs';
import { TranslateModule } from '@ngx-translate/core';

@Component({
  selector: 'app-coming-soon',
  standalone: true,
  imports: [TranslateModule, CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './coming-soon.component.html',
  styleUrls: ['./coming-soon.component.scss']
})
export class ComingSoonComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;

  form = this.fb.group(
    {
      email: ['', [Validators.required, Validators.pattern(patterns?.email)]],
    },
    { updateOn: "blur" }
  );
  get formControls(): any {
    return this.form?.controls;
  }
  isLoadingBtn: boolean = false;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    public publicService: PublicService,
    private alertsService: AlertsService,
    private homeService: HomeService,
    private fb: FormBuilder
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
  }

  submit(): void {
    if (this.form?.valid) {
      if (isPlatformBrowser(this.platformId)) {
      this.publicService?.show_loader?.next(true);
      }
      let data = {
        email: this.form?.value?.email
      };
      this.homeService?.subscribe(data)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.form.reset();
            res?.message ? this.alertsService?.openToast('success', res?.message) : '';
            this.publicService?.show_loader?.next(false);
          } else {
            this.publicService?.show_loader?.next(false);
            res?.message ? this.alertsService?.openToast('error', res?.message) : '';
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err?.message) : '';
          if (isPlatformBrowser(this.platformId)) {
          this.publicService?.show_loader?.next(false);
          }
        }
      );
    } else {
      this.publicService.validateAllFormFields(this.form);
    }
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
