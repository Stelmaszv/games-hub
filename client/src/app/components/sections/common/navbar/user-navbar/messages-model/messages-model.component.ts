import { Component } from '@angular/core';
import { TranslationService } from 'src/app/services/common/translation/translation.service';

@Component({
  selector: 'messages-model',
  templateUrl: './messages-model.component.html',
  styleUrls: ['./messages-model.component.scss']
})
export class MessagesModelComponent {
  constructor(public translationService: TranslationService) {}
}
