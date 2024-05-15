import { NgModule, LOCALE_ID } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule} from '@angular/common/http';
import { AppRoutingModule } from './app-routing.module';

import { TranslatePipe } from './pipe/common/TranslatePipe'

import { AppComponent } from './app.component';
import { PublishersComponent } from './components/sections/publishers/publishers.component';
import { NavbarComponent } from './components/sections/common/navbar/navbar.component';
import { UserNavbarComponent } from './components/sections/common/navbar/user-navbar/user-navbar.component';
import { UserProfilModalComponent } from './components/sections/common/navbar/user-navbar/user-profil-modal/user-profil-modal.component';

import { TranslationService } from './services/translation/translation.service';

import { registerLocaleData } from '@angular/common';
import localePl from '@angular/common/locales/pl';
import { MessagegesModelComponent } from './components/sections/common/navbar/user-navbar/messageges-model/messageges-model.component';
import { NotificationModelComponent } from './components/sections/common/navbar/user-navbar/notification-model/notification-model.component';

registerLocaleData(localePl);

@NgModule({
  declarations: [
    AppComponent,
    PublishersComponent,
    NavbarComponent,
    UserNavbarComponent,
    UserProfilModalComponent,
    TranslatePipe,
    MessagegesModelComponent,
    NotificationModelComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    AppRoutingModule
  ],
  providers: [{ provide: LOCALE_ID, useValue: 'pl' },TranslationService],
  bootstrap: [AppComponent]
})
export class AppModule { }
