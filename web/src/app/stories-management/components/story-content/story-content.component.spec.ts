import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StoryContentComponent } from './story-content.component';

describe('StoryContentComponent', () => {
  let component: StoryContentComponent;
  let fixture: ComponentFixture<StoryContentComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [StoryContentComponent]
    });
    fixture = TestBed.createComponent(StoryContentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
