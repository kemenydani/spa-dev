
import Connection from '../core/Connection';

const API_BASE = '/api/category/';

export default class CategoryDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
	
}
