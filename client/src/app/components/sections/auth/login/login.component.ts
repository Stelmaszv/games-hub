import { Component } from '@angular/core';
import { TranslationService } from 'src/app/services/translation/translation.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {

  email: string = '';
  password: string = '';
  loginFailed: boolean = false;
  
  constructor(public translationService: TranslationService) {}

  onSubmit() {}
}
