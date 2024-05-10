import { Component } from '@angular/core';
import {HttpServiceService} from '../../../../app/sevices/http-service/http-service.service'

@Component({
  selector: 'app-publishers',
  templateUrl: './publishers.component.html',
  styleUrls: ['./publishers.component.scss']
})
export class PublishersComponent {
  constructor(private httpServiceService: HttpServiceService) { }

  ngOnInit(): void {
    this.httpServiceService.getData('http://localhost/api/developer/show/29').subscribe(data => {
      console.log(data); // Tutaj możesz przetwarzać dane
    });
  }
}
