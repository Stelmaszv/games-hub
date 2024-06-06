import { TestBed } from '@angular/core/testing';

import { CanAddPublishersGuard } from './can-add-publishers.guard';

describe('CanAddPublishersGuard', () => {
  let guard: CanAddPublishersGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(CanAddPublishersGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
