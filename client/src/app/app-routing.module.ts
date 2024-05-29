import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/sections/auth/login/login.component';
import { NotLoginGuard } from './gards/not-login.guard';
import { RegisterComponent } from './components/sections/auth/register/register.component';
import { ForgotPasswordComponent } from './components/sections/auth/forgot-password/forgot-password.component';
import { MainComponentComponent } from './components/sections/main-component/main-component.component';
import { GamesMainListComponent } from './components/sections/games/games-main-list/games-main-list.component';
import { PublishersMainListComponent } from './components/sections/publishers/publishers-main-list/publishers-main-list.component';
import { DeveloperMainListComponent } from './components/sections/devloper/developer-main-list/developer-main-list.component';
import { ShowPublisherComponent } from './components/sections/publishers/show-publisher/show-publisher.component';
import { CanShowPublisherGuard } from './gards/section/publisher/CanShowPublisherGuard/can.show.publisher.guard';
import { CanEditPublisherGuard } from './gards/section/publisher/CanEditPublisherGuard/can-edit-publisher.guard';
import { CanDeletePublisherGuard } from './gards/section/publisher/CanDeletePublisherGuard/can-delete-publisher-guard';

const routes: Routes = [
  { path: '', component: MainComponentComponent },

  //publishers
  { path: 'publishers/list', component: PublishersMainListComponent },
  { path: 'publisher/show/:id', component: ShowPublisherComponent , canActivate: [CanShowPublisherGuard] },
  { path: 'publisher/edit/:id', component: ShowPublisherComponent , canActivate: [CanEditPublisherGuard] },
  { path: 'publisher/delete/:id', component: ShowPublisherComponent , canActivate: [CanDeletePublisherGuard] },

  //developers
  { path: 'developers/list', component: DeveloperMainListComponent },

  //games
  { path: 'games/list', component: GamesMainListComponent },
  
  //auth
  { path: 'login', component: LoginComponent, canActivate: [NotLoginGuard] },
  { path: 'register', component: RegisterComponent, canActivate: [NotLoginGuard] },
  { path: 'forgot-password', component: ForgotPasswordComponent, canActivate: [NotLoginGuard] }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }