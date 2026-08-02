import { TranslationChildModule } from '../../../../services/translation-child.module';
import { PlacesService } from '../../../../services/places.service';
import { Component, OnInit, Output, EventEmitter } from '@angular/core';
import { environment } from './../../../../../environments/environment';
import { AlertsService } from 'src/app/services/alerts.service';
import { HomeService } from '../../../../services/home.service';
import { PublicService } from '../../services/public.service';
import { patterns } from '../../configs/patternValidation';
import { FormBuilder, Validators } from '@angular/forms';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { Router } from '@angular/router';
import { Subscription } from 'rxjs';

@Component({
  standalone: true,
  imports: [CommonModule, TranslationChildModule, NgOptimizedImage],
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.scss']
})
export class FooterComponent implements OnInit {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;

  @Output() openPlaceHandler = new EventEmitter();

  isLoadingBtn: boolean = false;
  categoriesList: any = [];

  storesCategoriesList: any = [];
  restaurantCategories: any = [];

  emailForm = this.fb.group(
    {
      email: ['', [Validators.required, Validators.pattern(patterns?.email)]],
    },
    { updateOn: "blur" }
  );
  get formControls(): any {
    return this.emailForm?.controls;
  }

  constructor(
    private alertsService: AlertsService,
    private placesService: PlacesService,
    public publicService: PublicService,
    private homeService: HomeService,
    private fb: FormBuilder,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.placesService.categoriesListSubj.subscribe((res: any) => {
      res ? this.categoriesList = this.publicService?.slicedData(res, 4) : '';
    });
    this.publicService.storeSubjCategory.subscribe((res: any) => {
      res ? this.storesCategoriesList = this.publicService?.slicedData(res, 4) : '';
    });
    this.publicService.restaurantSubjCategory.subscribe((res: any) => {
      res ? this.restaurantCategories = this.publicService?.slicedData(res, 4) : '';
    });
  }

  openPlaceWithCategoryId(id?: any): void {
    this.openPlaceHandler?.emit();
    this.router?.navigate(['/places', { categoryId: id?.id }]);
    id ? this.publicService?.placeCategory?.next(id) : '';
  }
  openStoresWithCategoryId(storeCat?: any): void {
    this.router?.navigate(['/stores/list', { category: storeCat?.id }]);
    this.publicService?.storeCategory?.next(storeCat)
  }
  openRestaurantWithCategoryId(category?: any): void {
    this.router?.navigate(['/restaurants/list', { category: category?.id }]);
    this.publicService?.restaurantCategory?.next(category)
  }

  submit(): void {
    if (this.emailForm?.valid) {
      this.isLoadingBtn = true;
      let data = {
        email: this.emailForm?.value?.email
      };
      this.homeService?.subscribe(data)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.emailForm.reset();
            res?.message ? this.alertsService?.openToast('success', res?.message) : '';
            this.isLoadingBtn = false;
          } else {
            res?.message ? this.alertsService?.openToast('error', res?.message) : '';
            this.isLoadingBtn = false;
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoadingBtn = false;
        }
      );
    } else {
      this.publicService.validateAllFormFields(this.emailForm);
    }
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
