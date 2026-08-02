import { CommonModule, NgOptimizedImage } from '@angular/common';
import { NgModule } from '@angular/core';

import { ComingSoonModalComponent } from './components/coming-soon-modal/coming-soon-modal.component';
import { ShareButtonComponent } from './components/share-button/share-button.component';
import { MapComponent } from '../../components/home-page/components/map/map.component';
import { FooterComponent } from './components/footer/footer.component';
import { ShareComponent } from './components/share/share.component';
import { SharedComponent } from './shared.component';

import { TranslationChildModule } from '../../services/translation-child.module';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SharedRoutingModule } from './shared-routing.module';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { SkeletonModule } from 'primeng/skeleton';
import { SidebarModule } from 'primeng/sidebar';
import { RegionModalComponent } from './components/region-modal/region-modal.component';
import { CityModalComponent } from './components/city-modal/city-modal.component';
import { SkeletonComponent } from './components/skeleton/skeleton.component';
import { LazyLoadDirectiveNotStandAlone } from 'src/app/shared/directives/lazy-load.directive';
import { LazyLoadSectionDirectiveNotStandAlone } from 'src/app/shared/directives/lazyLoad-section.directive';
import { ThemeComponent } from './components/header/components/theme/theme.component';
import { TranslateModule } from '@ngx-translate/core';
import { ClickOutsideDirective } from './components/header/directives/click-outside.directive';


const components = [
  ComingSoonModalComponent,
  ShareButtonComponent,
  RegionModalComponent,
  CityModalComponent,
  ShareComponent,
  LazyLoadDirectiveNotStandAlone,
  LazyLoadSectionDirectiveNotStandAlone,


]
const modules = [
  TranslationChildModule,
  ReactiveFormsModule,
  ConfirmDialogModule,
  FooterComponent,
  SkeletonModule,
  SidebarModule,
  MapComponent,
  FormsModule,
  NgOptimizedImage,
  SkeletonComponent
]
@NgModule({
  declarations: [
    SharedComponent,
    ...components,
    ThemeComponent,
    ClickOutsideDirective,
  ],
  imports: [
    CommonModule,
    SharedRoutingModule,
    TranslateModule,
    ...modules
  ],
  exports: [
    ...components,
    ...modules
  ]
})
export class SharedModule { }
