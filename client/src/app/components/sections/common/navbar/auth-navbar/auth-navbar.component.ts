import { Component } from '@angular/core';
import { TranslationService } from 'src/app/services/translation/translation.service';

@Component({
  selector: 'auth-navbar',
  templateUrl: './auth-navbar.component.html',
  styleUrls: ['./auth-navbar.component.scss']
})
export class AuthNavbarComponent {
  constructor(public translationService: TranslationService) {}
}
