import { Component } from '@angular/core';
import { TranslationService } from 'src/app/services/common/translation/translation.service';
import { HttpErrorResponse } from '@angular/common/http';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { AuthService } from 'src/app/services/common/auth/auth.service';
import { FormValidatorService } from 'src/app/services/common/form-validator/form-validator.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent {

  public showPasswordIcon :string = 'fa-solid fa-eye' // fa-sharp fa-solid fa-eye-slash
  public showPasswordRepartIcon :string = 'fa-solid fa-eye' // fa-sharp fa-solid fa-eye-slash
  public errorMessage: string = '';
  public email: string | null = '';
  public password: string | null = '';
  public repartPassword: string | null = '';
  public registerFailed: boolean  | null = false;

  constructor(
    public translationService: TranslationService, 
    private httpServiceService: HttpServiceService, 
    private authService : AuthService,
    private formValidatorService: FormValidatorService
  ) {}

  showPassword(): void {
    const inputElement = document.querySelector<HTMLInputElement>('#password');
  
    if (!inputElement) {
      console.error("Input element '#password' not found.");
      return;
    }
  
    this.showPasswordIcon = (inputElement.type === 'password') ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
    inputElement.type = (inputElement.type === 'password') ? 'text' : 'password';
  }
  
  showRepartPassword(): void {
    const inputElement = document.querySelector<HTMLInputElement>('#repartPassword');
  
    if (!inputElement) {
      console.error("Input element '#repartPassword' not found.");
      return;
    }
  
    this.showPasswordRepartIcon = (inputElement.type === 'password') ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
    inputElement.type = (inputElement.type === 'password') ? 'text' : 'password';
  }

  onSubmit() {
    this.httpServiceService.postData('http://localhost/api/register',{ 
      'email' : this.email,
      'password':this.password,
      'repartPassword':this.repartPassword
    }).subscribe({
      next: (response) => {
        this.registerFailed = false;
        this.authService.setToken(response.token)
        location.reload(); 
      },
      error: (errorList: HttpErrorResponse) => {
        this.formValidatorService.setForm('registerForm')
        this.formValidatorService.showErrors(errorList.error.errors)
        this.formValidatorService.restNotUseInputs(errorList.error.errors)
      }
    });
  }
}
