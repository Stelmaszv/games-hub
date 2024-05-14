import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {

  title = 'client';
  isVisibleLeftMenu: boolean = true;
  isVisibleRightMenu: boolean = true;

  toggleLeftMenu() {
    this.isVisibleLeftMenu = !this.isVisibleLeftMenu;
  }

  toggleRightMenu() {
    this.isVisibleRightMenu = !this.isVisibleRightMenu;
  }

}
