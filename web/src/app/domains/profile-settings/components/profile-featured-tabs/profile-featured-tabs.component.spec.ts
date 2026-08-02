import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProfileFeaturedTabsComponent } from './profile-featured-tabs.component';

describe('ProfileFeaturedTabsComponent', () => {
  let component: ProfileFeaturedTabsComponent;
  let fixture: ComponentFixture<ProfileFeaturedTabsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ProfileFeaturedTabsComponent]
    });
    fixture = TestBed.createComponent(ProfileFeaturedTabsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
