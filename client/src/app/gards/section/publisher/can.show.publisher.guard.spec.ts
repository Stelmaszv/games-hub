import { TestBed } from '@angular/core/testing';

import { CanShowPublisherGuard } from './can.show.publisher.guard';

describe('CanShowPublisherGuard', () => {
  let guard: CanShowPublisherGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(CanShowPublisherGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
