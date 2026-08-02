import { Component, OnInit } from '@angular/core';
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';

@Component({
  selector: 'app-share',
  templateUrl: './share.component.html',
  styleUrls: ['./share.component.scss']
})
export class ShareComponent implements OnInit {
  public link: string = '';
  isCopied: boolean = false;

  constructor(
    private config: DynamicDialogConfig,
    private ref: DynamicDialogRef
  ) { }

  ngOnInit(): void {
    this.link = this.config?.data?.link ? this.config?.data?.link : '';
  }

  copyToClipboard(text: string): void {
    navigator.clipboard.writeText(text).then(() => {
      this.isCopied = true;
      setTimeout(() => {
        this.isCopied = false;
      }, 2000);
    }).catch(err => {
      console.error('Could not copy text: ', err);
    });
  }
}
