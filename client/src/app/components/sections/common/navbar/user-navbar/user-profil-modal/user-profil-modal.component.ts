import { Component } from '@angular/core';
import { TranslationService } from 'src/app/services/translation/translation.service';

@Component({
  selector: 'user-profil-modal',
  templateUrl: './user-profil-modal.component.html',
  styleUrls: ['./user-profil-modal.component.scss']
})
export class UserProfilModalComponent {
  constructor(public translationService: TranslationService) {}
}
