import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { Component, EventEmitter, Inject, Output, PLATFORM_ID } from '@angular/core';
import { Validators, FormBuilder, ReactiveFormsModule, FormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { AlertsService } from 'src/app/services/alerts.service';
import { HomeService } from 'src/app/services/home.service';
import { PlacesService } from 'src/app/services/places.service';
import { patterns } from '../../configs/patternValidation';
import { PublicService } from '../../services/public.service';
import { Subscription, tap } from 'rxjs';
import { Router } from '@angular/router';
import { catchError } from 'rxjs/operators';
import { MessageService } from 'primeng/api';

@Component({
  selector: 'app-new-footer',
  standalone: true,
  imports: [CommonModule, TranslateModule, ReactiveFormsModule, FormsModule, NgOptimizedImage],
  templateUrl: './new-footer.component.html',
  styleUrls: ['./new-footer.component.scss']
})
export class NewFooterComponent {
  private subscriptions: Subscription[] = [];
  @Output() openPlaceHandler = new EventEmitter();

  isLoadingBtn: boolean = false;

  placesCategoriesList: any = [];
  storesCategoriesList: any = [];
  restaurantCategories: any = [];

  emailForm = this.fb.group(
    {
      email: ['', [Validators.required, Validators.pattern(patterns?.email)]],
    },
    { updateOn: "blur" }
  );
  get emailFormControls(): any {
    return this.emailForm?.controls;
  }

  constructor(
    private messageService: MessageService,
    private alertsService: AlertsService,
    private placesService: PlacesService,
    public publicService: PublicService,
    private homeService: HomeService,
    private fb: FormBuilder,
    private router: Router,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.placesService.categoriesListSubj.subscribe((res: any) => {
        res ? this.placesCategoriesList = this.publicService?.slicedData(res, 4) : '';
      });
      this.publicService.storeSubjCategory.subscribe((res: any) => {
        res ? this.storesCategoriesList = this.publicService?.slicedData(res, 4) : '';
      });
      this.publicService.restaurantSubjCategory.subscribe((res: any) => {
        res ? this.restaurantCategories = this.publicService?.slicedData(res, 4) : '';
      });
    }
  }

  openPlaceWithCategoryId(id?: any): void {
    if (isPlatformBrowser(this.platformId)) {
      this.openPlaceHandler?.emit();
      this.router?.navigate(['/places'], { queryParams: { categoryId: id?.id } });
      if (id) {
        this.publicService?.placeCategory?.next(id);
      }
    }
  }

  openStoresWithCategoryId(storeCat?: any): void {
    if (isPlatformBrowser(this.platformId)) {
      this.router?.navigate(['/stores/list'], { queryParams: { categoryId: storeCat?.id } });
      this.publicService?.storeCategory?.next(storeCat)
    }
  }
  openRestaurantWithCategoryId(category?: any): void {
    if (isPlatformBrowser(this.platformId)) {
      this.router?.navigate(['/restaurants/list'], { queryParams: { categoryId: category?.id } });
      this.publicService?.restaurantCategory?.next(category)
    }
  }

  // Start Send Email
  submit(): void {
    this.messageService.clear();
    if (this.emailForm?.valid) {
      this.isLoadingBtn = true;
      const data = {
        email: this.emailForm?.value?.email
      };
      this.subscribeEmail(data);
    } else {
      let error = '';
      if (this.emailFormControls?.email?.errors?.required) {
        error = 'validations.emailRequired';
      }
      if (this.emailFormControls?.email?.errors?.pattern) {
        error = 'validations.emailNotValid';
      }
      this.alertsService?.openToast('error', this.publicService.translateTextFromJson(error));
    }
  }
  private subscribeEmail(data: any): void {
    let subscribeSendEmail: Subscription = this.homeService?.subscribe(data).pipe(
      tap(res => this.handleSendEmailSuccess(res)),
      catchError(err => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(subscribeSendEmail);
  }
  private handleSendEmailSuccess(response: any): void {
    if (response?.code == 200) {
      this.emailForm.reset();
      this.handleSuccess(response?.message);
    } else {
      this.handleError(response?.message);
    }
    this.isLoadingBtn = false;
  }
  // End Send Email

  /* --- Handle api requests messages --- */
  private handleSuccess(msg: any): any {
    this.setMessage(msg || this.publicService.translateTextFromJson('general.successRequest'), 'success');
  }
  private handleError(err: any): any {
    this.setMessage(err || this.publicService.translateTextFromJson('general.errorOccur'), 'error');
  }
  private setMessage(message: string, type: string): void {
    if (isPlatformBrowser(this.platformId)) {
      this.alertsService.openToast(type, message);
    }
  }

  ngOnDestroy(): void {
    this.subscriptions?.forEach((subscription: Subscription) => subscription?.unsubscribe());
  }
}
