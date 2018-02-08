
import Connection from '../core/Connection';

const API_BASE = '/api/article/';

export default class ArticleDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
	
}
