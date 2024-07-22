import { Component, Input } from '@angular/core';

@Component({
  selector: 'show-more',
  templateUrl: './show-more.component.html',
  styleUrls: ['./show-more.component.scss']
})
export class ShowMoreComponent {
  @Input() content!: string | null | undefined;
}
