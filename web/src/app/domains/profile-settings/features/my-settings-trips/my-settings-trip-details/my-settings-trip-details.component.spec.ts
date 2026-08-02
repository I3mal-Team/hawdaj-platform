import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MySettingsTripDetailsComponent } from './my-settings-trip-details.component';

describe('MySettingsTripDetailsComponent', () => {
  let component: MySettingsTripDetailsComponent;
  let fixture: ComponentFixture<MySettingsTripDetailsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [MySettingsTripDetailsComponent]
    });
    fixture = TestBed.createComponent(MySettingsTripDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
