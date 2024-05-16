import { NgModule} from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule} from '@angular/common/http';
import { AppRoutingModule } from './app-routing.module';

import { TranslationService } from './services/translation/translation.service';

import { AppComponent } from './app.component';
import { PublishersComponent } from './components/sections/publishers/publishers.component';
import { NavbarComponent } from './components/sections/common/navbar/navbar.component';
import { UserNavbarComponent } from './components/sections/common/navbar/user-navbar/user-navbar.component';
import { UserProfilModalComponent } from './components/sections/common/navbar/user-navbar/user-profil-modal/user-profil-modal.component';
import { MessagegesModelComponent } from './components/sections/common/navbar/user-navbar/messageges-model/messageges-model.component';
import { NotificationModelComponent } from './components/sections/common/navbar/user-navbar/notification-model/notification-model.component';
import { LoginComponent } from './components/sections/auth/login/login.component';
import { AuthNavbarComponent } from './components/sections/common/navbar/auth-navbar/auth-navbar.component';

@NgModule({
  declarations: [
    AppComponent,
    PublishersComponent,
    NavbarComponent,
    UserNavbarComponent,
    UserProfilModalComponent,
    MessagegesModelComponent,
    NotificationModelComponent,
    LoginComponent,
    AuthNavbarComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    AppRoutingModule
  ],
  providers: [TranslationService],
  bootstrap: [AppComponent]
})
export class AppModule { }
