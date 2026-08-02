import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StoryDetailsComponent } from './story-details.component';

describe('StoryDetailsComponent', () => {
  let component: StoryDetailsComponent;
  let fixture: ComponentFixture<StoryDetailsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [StoryDetailsComponent]
    });
    fixture = TestBed.createComponent(StoryDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
