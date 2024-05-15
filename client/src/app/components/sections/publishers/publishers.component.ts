import { Component, Input  } from '@angular/core';
import { HttpServiceService } from '../../../../app/services/http-service/http-service.service'
import { IsGrantedService } from '../../../../app/services/is-granted/is-granted.service'

@Component({
  selector: 'app-publishers',
  templateUrl: './publishers.component.html',
  styleUrls: ['./publishers.component.scss']
})

export class PublishersComponent {
  @Input() isGranted: boolean | undefined;
  constructor(private httpServiceService: HttpServiceService,private isGrantedService : IsGrantedService) { }

  ngOnInit(): void {
    this.isGrantedService.setData('CAN_SHOW_PUBLISHER', { "entity": "Publisher", "id": 65 });
    
    this.isGrantedService.responseData.subscribe((el) => {
      this.isGranted = el
    });

  }
}
