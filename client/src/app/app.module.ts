import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';

import { AppRoutingModule } from './app-routing.module';

import { AppComponent } from './app.component';
import { PublishersComponent } from './components/sections/publishers/publishers.component';
import { NavbarComponent } from './components/sections/common/navbar/navbar.component';
import { UserNavbarComponent } from './components/sections/common/navbar/user-navbar/user-navbar.component';

@NgModule({
  declarations: [
    AppComponent,
    PublishersComponent,
    NavbarComponent,
    UserNavbarComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    AppRoutingModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
