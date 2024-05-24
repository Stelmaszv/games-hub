import { Component } from '@angular/core';
import { TranslationService } from 'src/app/services/common/translation/translation.service';

@Component({
  selector: 'add-publishers',
  templateUrl: './add-publishers.component.html',
  styleUrls: ['./add-publishers.component.scss']
})
export class AddPublishersComponent {
  constructor(public translationService: TranslationService){}
}
