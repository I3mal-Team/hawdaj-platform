import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { createVersionedTranslateLoader } from 'src/app/core/utils/versioned-translate-loader';
import { HttpClient } from '@angular/common/http';



@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    TranslateModule.forChild({
      loader: {
        provide: TranslateLoader,
        useFactory: createVersionedTranslateLoader,
        deps: [HttpClient]
      },
    }),
  ],
  exports: [
    TranslateModule
  ]
})
export class TranslationChildModule { }
