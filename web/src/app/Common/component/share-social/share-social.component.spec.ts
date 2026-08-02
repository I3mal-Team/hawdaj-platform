import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ShareSocialComponent } from './share-social.component';

describe('ShareSocialComponent', () => {
  let component: ShareSocialComponent;
  let fixture: ComponentFixture<ShareSocialComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ShareSocialComponent]
    });
    fixture = TestBed.createComponent(ShareSocialComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
