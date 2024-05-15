import { Component } from '@angular/core';
import { TranslationService } from 'src/app/services/translation/translation.service';

@Component({
  selector: 'messageges-model',
  templateUrl: './messageges-model.component.html',
  styleUrls: ['./messageges-model.component.scss']
})
export class MessagegesModelComponent {
  constructor(public translationService: TranslationService) {}
}
