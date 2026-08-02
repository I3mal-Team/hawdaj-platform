import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PopularAppsComponent } from './popular-apps.component';

describe('PopularAppsComponent', () => {
  let component: PopularAppsComponent;
  let fixture: ComponentFixture<PopularAppsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [PopularAppsComponent]
    });
    fixture = TestBed.createComponent(PopularAppsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
