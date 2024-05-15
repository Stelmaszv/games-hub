import { Component } from '@angular/core';
import { TranslationService } from 'src/app/services/translation/translation.service';

@Component({
  selector: 'notification-model',
  templateUrl: './notification-model.component.html',
  styleUrls: ['./notification-model.component.scss']
})
export class NotificationModelComponent {
  constructor(public translationService: TranslationService) {}
}
