import { ThisReceiver } from '@angular/compiler';
import { Component, OnInit  } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Publisher, PublisherListElement } from 'src/app/interface/publisher';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';

@Component({
  selector: 'app-show-publisher',
  templateUrl: './show-publisher.component.html',
  styleUrls: ['./show-publisher.component.scss']
})
export class ShowPublisherComponent implements OnInit {
  public publisher: Publisher | null = null;

  constructor(private route: ActivatedRoute, private HttpServiceService : HttpServiceService) { }

  ngOnInit(): void {
    this.getPublisher()
  }
  
  private getPublisher(){
    this.route.params.subscribe(params => {
      this.HttpServiceService.getData('http://localhost/api/publisher/show/'+params['id'])
      .subscribe((publisher: Publisher ) => {
        this.publisher = publisher
      });
    });
  }
}