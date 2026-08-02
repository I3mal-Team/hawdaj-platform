import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MyPropertiesListingComponent } from './my-properties-listing.component';

describe('MyPropertiesListingComponent', () => {
  let component: MyPropertiesListingComponent;
  let fixture: ComponentFixture<MyPropertiesListingComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [MyPropertiesListingComponent]
    });
    fixture = TestBed.createComponent(MyPropertiesListingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
