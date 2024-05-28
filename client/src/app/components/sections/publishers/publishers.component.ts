import { Component, Input  } from '@angular/core';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Component({
  selector: 'app-publishers',
  templateUrl: './publishers.component.html',
  styleUrls: ['./publishers.component.scss']
})

export class PublishersComponent {
  @Input() isGranted: boolean | undefined;
  constructor(private httpServiceService: HttpServiceService,private isGrantedService : IsGrantedService) { }
}
