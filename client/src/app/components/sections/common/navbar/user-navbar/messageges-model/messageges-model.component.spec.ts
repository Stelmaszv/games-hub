import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MessagegesModelComponent } from './messageges-model.component';

describe('MessagegesModelComponent', () => {
  let component: MessagegesModelComponent;
  let fixture: ComponentFixture<MessagegesModelComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MessagegesModelComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(MessagegesModelComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
