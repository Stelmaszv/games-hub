import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ShowPublisherComponent } from './show-publisher.component';

describe('ShowPublisherComponent', () => {
  let component: ShowPublisherComponent;
  let fixture: ComponentFixture<ShowPublisherComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShowPublisherComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ShowPublisherComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
