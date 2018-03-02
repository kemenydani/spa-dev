
import Connection from '../core/Connection';

const API_BASE = '/api/comment/';

export default class CommentDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
	
}
