import { Component } from '@angular/core';
import { AuthService } from 'src/app/services/auth/auth.service';
import { HttpServiceService } from 'src/app/services/http-service/http-service.service';
import { TranslationService } from 'src/app/services/translation/translation.service';
import { HttpErrorResponse } from '@angular/common/http';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent {

  public errorMessage: string = '';
  public email: string | null = 'user@qwe.com';
  public password: string | null = '123';
  public passwordRepeat: string | null = '123';
  public registerFailed: boolean  | null = false;

  constructor(
    public translationService: TranslationService, 
    private httpServiceService: HttpServiceService, 
    private authService : AuthService
  ) {}

  onSubmit() {
    this.httpServiceService.postData('http://localhost/api/login',{ 
      'email' : this.email,
      'password':this.password
    }).subscribe({
      next: (response) => {
        this.registerFailed = false;
        this.authService.setToken(response.token)
        location.reload(); 
      },
      error: (error: HttpErrorResponse) => {
        this.registerFailed = true;
        this.errorMessage = error.error.message || 'An unknown error occurred';
      }
    });
  }
}
