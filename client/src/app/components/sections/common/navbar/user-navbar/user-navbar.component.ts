import { Component } from '@angular/core';

@Component({
  selector: 'user-navbar',
  templateUrl: './user-navbar.component.html',
  styleUrls: ['./user-navbar.component.scss']
})
export class UserNavbarComponent {
  userMessage:number = 4;
  userNotyfication:number = 0;
}
