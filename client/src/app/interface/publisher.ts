export interface PublisherListElement extends Publisher,PublisherPermisions {}

export interface Publisher{
  id: number;

  generalInformation: {
    name: string;
  };
}

export interface PublisherPermisions{
  canShowPublisher: boolean;
  canEditPublisher: boolean;
  canDeletePublisher: boolean;
}