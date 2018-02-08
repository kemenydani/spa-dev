
import Connection from '../core/Connection';

const API_BASE = '/api/forum/';

export default class ForumDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
	
}
