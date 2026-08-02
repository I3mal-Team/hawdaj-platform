import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProfileAsideMenuComponent } from './profile-aside-menu.component';

describe('ProfileAsideMenuComponent', () => {
  let component: ProfileAsideMenuComponent;
  let fixture: ComponentFixture<ProfileAsideMenuComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ProfileAsideMenuComponent]
    });
    fixture = TestBed.createComponent(ProfileAsideMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
