import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StoreDetailsComponent } from './store-details.component';

describe('StoreDetailsComponent', () => {
  let component: StoreDetailsComponent;
  let fixture: ComponentFixture<StoreDetailsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [StoreDetailsComponent]
    });
    fixture = TestBed.createComponent(StoreDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
