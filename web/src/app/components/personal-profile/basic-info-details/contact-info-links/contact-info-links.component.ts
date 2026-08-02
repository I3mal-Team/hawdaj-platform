import { AddContactInfoModalComponent } from '../../add-contact-info-modal/add-contact-info-modal.component';
import { PublicService } from './../../../../modules/shared/services/public.service';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { CommonModule } from '@angular/common';
import { SvgIconComponent } from 'src/app/shared/components/svg-icon/svg-icon.component';

@Component({
  selector: 'app-contact-info-links',
  standalone: true,
  imports: [CommonModule, TranslateModule, SvgIconComponent],
  templateUrl: './contact-info-links.component.html',
  styleUrls: ['./contact-info-links.component.scss']
})
export class ContactInfoLinksComponent {
  @Input() items: any = [];
  @Input() type: string = 'normal';
  @Output() changeContactInfoHandler = new EventEmitter();
  linksContact: any = [];

  constructor(
    private dialogService: DialogService,
    private publicService: PublicService
  ) { }

  ngOnInit(): void {
    const socialPlatforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
    if (this.type == 'normal') {
      socialPlatforms.push('tiktok');
    } else {
      socialPlatforms.push('personal_account');
    }
    const userSocialsArray: any = [];
    const user = this.items[0]; // Assuming we are working with the first user in the array
    if (user) {
      socialPlatforms?.forEach(platform => {
        if (user[platform]) {
          userSocialsArray.push({ name: { id: platform, title: platform }, link: user[platform] });
        }
      });
    }
    this.linksContact = userSocialsArray;
  }

  addContactInfo(item?: any): void {
    let termsRef: any;
    if (item) {
      termsRef = this.dialogService?.open(AddContactInfoModalComponent, {
        data: {
          el: item,
          type: this.type,
          isEdit: true
        },
        width: '40%',
        dismissableMask: false,
        styleClass: 'custom-modal',
        showHeader: false
      });
    } else {
      termsRef = this.dialogService?.open(AddContactInfoModalComponent, {
        width: '40%',
        data: {
          type: this.type,
          isEdit: false
        },
        dismissableMask: false,
        styleClass: 'custom-modal',
        showHeader: false
      });
    }
    termsRef.onClose.subscribe((result: any) => {
      if (result?.item) {
        if (this.linksContact?.length === 0) {
          let addedItem = result?.item;
          addedItem["id"] = 1;
          this.linksContact?.push(addedItem);
        } else {
          let founded;
          let foundedItem;
          this.linksContact?.filter((value: any, index: any) => {
            if (value?.name === result?.item?.name) {
              founded = true;
              foundedItem = index;
            }
          });
          if (founded !== true) {
            let addedItem = result?.item;
            addedItem["id"] = Math.round(Math.random() * 1000);
            this.linksContact?.push(addedItem);
          } else {
            this.linksContact[`${foundedItem}`]["link"] = result?.item?.link;
          }
        }
        this.changeContactInfoHandler.emit(this.linksContact);
      }
    });
  }

  removeItemContact(item: any): void {
    this.linksContact = this.linksContact?.map((value: any) => {
      if (value?.name?.id === item?.name?.id) {
        // Set the link to null if the item matches
        return { ...value, link: null };
      }
      return value;
    });

    // Emit the updated array with the null link
    this.changeContactInfoHandler.emit(this.linksContact);
  }

  get validLinksCount(): number {
    return this.linksContact?.filter((item: any) => item?.link)?.length || 0;
  }

  get validLinks(): any[] {
    return this.linksContact?.filter((item: any) => item?.link) || [];
  }
}

