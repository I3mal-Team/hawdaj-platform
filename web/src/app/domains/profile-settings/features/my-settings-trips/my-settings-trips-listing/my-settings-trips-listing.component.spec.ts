import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MySettingsTripsListingComponent } from './my-settings-trips-listing.component';

describe('MySettingsTripsListingComponent', () => {
  let component: MySettingsTripsListingComponent;
  let fixture: ComponentFixture<MySettingsTripsListingComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [MySettingsTripsListingComponent]
    });
    fixture = TestBed.createComponent(MySettingsTripsListingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
