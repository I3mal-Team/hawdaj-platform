import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HomePageApplicationsV2Component } from './home-page-applications-v2.component';

describe('HomePageApplicationsV2Component', () => {
  let component: HomePageApplicationsV2Component;
  let fixture: ComponentFixture<HomePageApplicationsV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HomePageApplicationsV2Component]
    });
    fixture = TestBed.createComponent(HomePageApplicationsV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
