import { TestBed } from '@angular/core/testing';

import { CanListPublishersGuard } from './can-list-publishers.guard';

describe('CanListPublishersGuard', () => {
  let guard: CanListPublishersGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(CanListPublishersGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
