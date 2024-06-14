import { Component, OnInit  } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { Publisher } from '../interfaces';

@Component({
  selector: 'app-show-publisher',
  templateUrl: './show-publisher.component.html',
  styleUrls: ['./show-publisher.component.scss']
})
export class ShowPublisherComponent implements OnInit {
  public publisher: Publisher | null = null;

  public constructor(private route: ActivatedRoute, private HttpServiceService : HttpServiceService) { }

  public ngOnInit(): void {
    this.getPublisher()
  }

  private getPublisher(){
    this.route.params.subscribe(params => {
      this.HttpServiceService.getData('http://localhost/api/publisher/show/'+params['id']).subscribe((publisher: Publisher ) => {
        this.publisher = publisher
      });
    });
  }
}