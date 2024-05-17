import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { PublishersComponent } from '../app/components/sections/publishers/publishers.component';
import { LoginComponent } from './components/sections/auth/login/login.component';

const routes: Routes = [
  { path: '', component: PublishersComponent },
  { path: 'login', component: LoginComponent },
  { path: 'register', component: LoginComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }