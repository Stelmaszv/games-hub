export interface PublisherListElement extends PublisherPermisions {
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