import { ResponseList } from "../../interface";

export interface PublisherAddForm {
    generalInformation:PublisherGeneralInformation,
    descriptions:PublisherDescriptions, 
    add: boolean
}

export interface PublisherDescriptions {
    eng: null|string,
    pl: null|string,
    fr: null|string,
}

export interface PublisherGeneralInformation {
    name: string,
    origin: string|null,
    founded: string|null,
    website: string|null,
    headquarter: string|null
}

interface Description {
    url: string;
    lng: string;
}

export interface PublisherDescriptionsScraper{
    descriptions: Description[];
}

export interface GeneralInformationScraper{
    url : string
}

export interface GeneralInformationResponse extends Response{
    generalInformation: PublisherGeneralInformation,
}

export interface PublisherDescriptionsScraperResponse extends Response{
    description: PublisherDescriptions,
}

export interface Publisher{
    id: number;
  
    generalInformation: {
      name: string;
    };
}
  
export interface PublisherPermissions{
  canShowPublisher: boolean;
  canEditPublisher: boolean;
  canDeletePublisher: boolean;
}

export interface PublisherList extends ResponseList{
    results : PublisherListElement[]
}

export interface PublisherListElement extends Publisher,PublisherPermissions {}