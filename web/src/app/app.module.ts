import { interceptorProviders } from './services/interceptors/interceptor-index';

import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { ConfirmationService, MessageService } from 'primeng/api';
import { CUSTOM_ELEMENTS_SCHEMA, NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { GoogleMapsModule } from '@angular/google-maps';
import { AppRoutingModule } from './app-routing.module';
import { AsyncPipe, DatePipe } from '@angular/common';
import { DialogService } from 'primeng/dynamicdialog';
import { AppComponent } from './app.component';

import { AngularFireModule } from '@angular/fire/compat';
import { AngularFireAuthModule } from '@angular/fire/compat/auth';
import { environment } from '../environments/environment';
import { AngularFireAuth } from '@angular/fire/compat/auth';
import { LazyImgDirective } from './modules/shared/directives/lazy-img.directive';
import { createVersionedTranslateLoader } from './core/utils/versioned-translate-loader';

@NgModule({
  declarations: [
    AppComponent,
    LazyImgDirective
  ],
  imports: [
    BrowserModule.withServerTransition({ appId: 'my-app', }),
    AngularFireModule.initializeApp(environment.firebaseConfig),
    AngularFireAuthModule,

    BrowserAnimationsModule,
    AppRoutingModule,
    HttpClientModule,
    GoogleMapsModule,
    BrowserModule,
    TranslateModule.forRoot({
      loader: {
        provide: TranslateLoader,
        useFactory: createVersionedTranslateLoader,
        deps: [HttpClient],
      }
    })
  ],
  providers: [AngularFireAuth, DatePipe, AsyncPipe, DialogService, MessageService, ConfirmationService, interceptorProviders],
  bootstrap: [AppComponent],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],

})
export class AppModule { }
