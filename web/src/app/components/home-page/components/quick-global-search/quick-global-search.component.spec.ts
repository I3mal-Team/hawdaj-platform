import { ComponentFixture, TestBed } from '@angular/core/testing';

import { QuickGlobalSearchComponent } from './quick-global-search.component';

describe('QuickGlobalSearchComponent', () => {
  let component: QuickGlobalSearchComponent;
  let fixture: ComponentFixture<QuickGlobalSearchComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [QuickGlobalSearchComponent]
    });
    fixture = TestBed.createComponent(QuickGlobalSearchComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
