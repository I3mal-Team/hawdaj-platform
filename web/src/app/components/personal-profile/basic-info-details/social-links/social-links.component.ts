import { AddSocialModalComponent } from '../../add-social-modal/add-social-modal.component';
import { PublicService } from './../../../../modules/shared/services/public.service';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-social-links',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './social-links.component.html',
  styleUrls: ['./social-links.component.scss']
})
export class SocialLinksComponent {
  @Input() items: any = [];
  @Input() type: string = 'normal';
  @Output() changeSocialLinksHandler = new EventEmitter();
  linksSocial: any = [];

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
        console.log(userSocialsArray)
      }
    });
  }
    this.linksSocial = userSocialsArray;
  }

  addSocialLink(item?: any): void {
    let termsRef: any;
    if (item) {
      termsRef = this.dialogService?.open(AddSocialModalComponent, {
        data: {
          el: item,
          type: 'tourGuide',
          isEdit: true
        },
        width: '40%',
        dismissableMask: false,
        styleClass: 'custom-modal',
        header: this.publicService.translateTextFromJson('profile.editSocialLink')
      });
    } else {
      termsRef = this.dialogService?.open(AddSocialModalComponent, {
        width: '40%',
        data: {
          type: 'tourGuide',
          isEdit: false
        },
        dismissableMask: false,
        styleClass: 'custom-modal',
        header: this.publicService.translateTextFromJson('profile.addSocialLink')
      });
    }
    termsRef.onClose.subscribe((result: any) => {
      if (result?.item) {
        if (this.linksSocial?.length === 0) {
          let addedItem = result?.item;
          addedItem["id"] = 1;
          this.linksSocial?.push(addedItem);
        } else {
          let founded;
          let foundedItem;
          this.linksSocial?.filter((value: any, index: any) => {
            if (value?.name === result?.item?.name) {
              founded = true;
              foundedItem = index;
            }
          });
          if (founded !== true) {
            let addedItem = result?.item;
            addedItem["id"] = Math.round(Math.random() * 1000);
            this.linksSocial?.push(addedItem);
          } else {
            this.linksSocial[`${foundedItem}`]["link"] = result?.item?.link;
          }
        }
        this.changeSocialLinksHandler.emit(this.linksSocial);
      }
    });
  }
  removeItemSocial(item: any): void {
    this.linksSocial = this.linksSocial?.map((value: any) => {
      if (value?.name?.id === item?.name?.id) {
        // Set the link to null if the item matches
        return { ...value, link: null };
      }
      return value;
    });
  
    // Emit the updated array with the null link
    this.changeSocialLinksHandler.emit(this.linksSocial);
  }  

}
