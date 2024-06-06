import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GamesMainListComponent } from './games-main-list.component';

describe('GamesMainListComponent', () => {
  let component: GamesMainListComponent;
  let fixture: ComponentFixture<GamesMainListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GamesMainListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(GamesMainListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
