import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { PublishersComponent } from '../app/components/sections/publishers/publishers.component';
import { LoginComponent } from './components/sections/auth/login/login.component';
import { NotLoginGuard } from './gards/not-login.guard';
import { RegisterComponent } from './components/sections/auth/register/register.component';
import { ForgotPasswordComponent } from './components/sections/auth/forgot-password/forgot-password.component';

const routes: Routes = [
  { path: '', component: PublishersComponent },
  { path: 'login', component: LoginComponent, canActivate: [NotLoginGuard] },
  { path: 'register', component: RegisterComponent, canActivate: [NotLoginGuard] },
  { path: 'forgot-password', component: ForgotPasswordComponent, canActivate: [NotLoginGuard] }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }