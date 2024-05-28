import { PaginatorData } from "./common"
import { PublisherListElement } from "./publisher"

export interface RespanseList {
    results : PublisherListElement[]
    paginatorData : PaginatorData
}

