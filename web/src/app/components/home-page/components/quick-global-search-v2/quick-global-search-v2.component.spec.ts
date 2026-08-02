import { ComponentFixture, TestBed } from '@angular/core/testing';

import { QuickGlobalSearchV2Component } from './quick-global-search-v2.component';

describe('QuickGlobalSearchV2Component', () => {
  let component: QuickGlobalSearchV2Component;
  let fixture: ComponentFixture<QuickGlobalSearchV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [QuickGlobalSearchV2Component]
    });
    fixture = TestBed.createComponent(QuickGlobalSearchV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
