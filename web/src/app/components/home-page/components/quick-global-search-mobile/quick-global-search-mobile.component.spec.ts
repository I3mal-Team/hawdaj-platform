import { ComponentFixture, TestBed } from '@angular/core/testing';

import { QuickGlobalSearchMobileComponent } from './quick-global-search-mobile.component';

describe('QuickGlobalSearchMobileComponent', () => {
  let component: QuickGlobalSearchMobileComponent;
  let fixture: ComponentFixture<QuickGlobalSearchMobileComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [QuickGlobalSearchMobileComponent]
    });
    fixture = TestBed.createComponent(QuickGlobalSearchMobileComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
